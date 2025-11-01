<?php

namespace App\Http\Controllers;

use App\Models\CvVersion;
use App\Services\AtsCheckerService;
use App\Services\CvGeneratorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomProfileController extends Controller
{
    protected AtsCheckerService $atsCheckerService;
    protected CvGeneratorService $cvGeneratorService;

    public function __construct(AtsCheckerService $atsCheckerService, CvGeneratorService $cvGeneratorService)
    {
        $this->atsCheckerService = $atsCheckerService;
        $this->cvGeneratorService = $cvGeneratorService;
    }

    public function show()
    {
        $user = Auth::guard('employer')->check() ? Auth::guard('employer')->user() : Auth::user();

        if (Auth::guard('employer')->check()) {
            $totalJobPosts = $user->posts()->count();
            $activeJobPosts = $user->posts()->whereNull('closed_at')->count();
            $closedJobPosts = $user->posts()->whereNotNull('closed_at')->count();
            $applicationsReceived = $user->posts()->withCount('applicants')->get()->sum('applicants_count');

            $recentJobPosts = $user->posts()
                ->withCount('applicants')
                ->orderByDesc('created_at')
                ->take(5)
                ->get();

            $activeRoles = $user->posts()
                ->withCount('applicants')
                ->whereNull('closed_at')
                ->orderByDesc('created_at')
                ->take(4)
                ->get();

            $latestApplicantActivities = collect(
                DB::table('post_user_applications as pua')
                    ->join('prosnap_posts.posts as posts', 'pua.post_id', '=', 'posts.id')
                    ->join('users', 'pua.user_id', '=', 'users.id')
                    ->where('posts.employer_id', $user->id)
                    ->orderByDesc('pua.created_at')
                    ->limit(5)
                    ->get([
                        'pua.created_at',
                        'pua.updated_at',
                        'pua.recruited',
                        'pua.declined',
                        'pua.cv_path',
                        'posts.id as post_id',
                        'posts.title as post_title',
                        'posts.location as post_location',
                        'posts.closed_at',
                        'users.id as user_id',
                        'users.name as user_name',
                        'users.email as user_email',
                    ])
            )->map(function ($activity) {
                $activity->created_at = $activity->created_at ? Carbon::parse($activity->created_at) : null;
                $activity->updated_at = $activity->updated_at ? Carbon::parse($activity->updated_at) : null;

                return $activity;
            });

            return view('profile.custom', compact(
                'user',
                'totalJobPosts',
                'activeJobPosts',
                'closedJobPosts',
                'applicationsReceived',
                'recentJobPosts',
                'activeRoles',
                'latestApplicantActivities'
            ));
        }

        $appliedPosts = $user->appliedPosts()->latest()->get();
        $cvVersions = $user->cvVersions()->orderBy('version_number', 'desc')->get();

        return view('profile.custom', compact('user', 'appliedPosts', 'cvVersions'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
            'company_description' => 'nullable|string|max:1000',
        ]);

        $user = Auth::guard('employer')->check() ? Auth::guard('employer')->user() : Auth::user();

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('public/profile_photos');
            $user->profile_photo_path = basename($path);
        }

        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->filled('company_description')) {
            $user->company_description = $request->company_description;
        }

        $user->save();

        $route = Auth::guard('employer')->check() ? 'employer.custom.profile.show' : 'custom.profile.show';

        return redirect()->route($route)->with('success', 'Profile updated successfully.');
    }

    public function analyzeCv(Request $request)
    {
        try {
            $input = $this->validateCvInput($request, true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        $user = Auth::user();
        $cvData = $this->prepareCvData($input, $user);
        $analysis = $this->atsCheckerService->analyze($cvData);

        return response()->json($analysis);
    }

    public function uploadCV(Request $request)
    {
        $request->validate([
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'cv_file.required' => 'Please upload a CV file.',
            'cv_file.file' => 'The uploaded file must be a valid file.',
            'cv_file.mimes' => 'The CV must be a file of type: pdf, doc, docx.',
            'cv_file.max' => 'The CV file size must not exceed 2MB.',
        ]);

        $path = $request->file('cv_file')->store('cvs', 'public');
        $user = Auth::user();
        $user->cv_path = $path;
        $user->save();

        return redirect()->route('custom.profile.show')->with('success', 'CV uploaded successfully.');
    }

    public function generateCv(Request $request)
    {
        $input = $this->validateCvInput($request);
        $user = Auth::user();

        if ($user->cvVersions()->count() >= 3) {
            return redirect()->back()
                ->withErrors(['cv_versions' => 'You can store up to 3 generated CVs. Delete an older version to create a new one.']);
        }

        $cvData    = $this->prepareCvData($input, $user);
        $analysis  = $this->atsCheckerService->analyze($cvData);
        $latestVersion = $user->cvVersions()->orderBy('version_number', 'desc')->first();
        $versionNumber = $latestVersion ? $latestVersion->version_number + 1 : 1;

        $cvData['analysis_snapshot'] = [
            'score' => $analysis['atsScore'] ?? null,
            'warnings' => $analysis['warnings'] ?? [],
            'highlights' => $analysis['highlights'] ?? [],
            'breakdown' => $analysis['scoreBreakdown'] ?? [],
        ];

        $cvData['meta']['version_number'] = $versionNumber;

        $verificationHash = hash('sha256', Str::uuid()->toString() . '|' . $user->getKey() . '|' . microtime(true));
        $verificationUrl  = route('cv.verify', ['hash' => $verificationHash]);
        $cvData['meta']['verification'] = [
            'hash' => $verificationHash,
            'url'  => $verificationUrl,
        ];

        $filePath = $this->cvGeneratorService->generate(
            $cvData,
            $input['template']
        );

        $storedVersion = CvVersion::create([
            'user_id' => $user->id,
            'file_path' => $filePath,
            'template' => $input['template'],
            'language' => 'en',
            'is_anonymized' => false,
            'sha256_hash' => $verificationHash,
            'version_number' => $versionNumber,
            'notes' => $input['notes'] ?? null,
            'data' => $cvData,
        ]);

        session()->flash('success', 'CV generated successfully.');

        return response()->download(Storage::disk('public')->path($filePath));
    }

    public function downloadCV(Request $request, $version = null)
    {
        $user = Auth::user();

        if ($version) {
            $cvVersion = CvVersion::where('user_id', $user->id)->where('id', $version)->firstOrFail();
            $filePath = $cvVersion->file_path;
        } else {
            $cvVersion = $user->cvVersions()->orderBy('version_number', 'desc')->firstOrFail();
            $filePath = $cvVersion->file_path;
        }

        $fullPath = Storage::disk('public')->path($filePath);

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'CV file not found.');
        }

        return response()->download($fullPath);
    }

    public function destroyCvVersion(CvVersion $version)
    {
        $user = Auth::user();

        if ($version->user_id !== $user->id) {
            abort(403);
        }

        Storage::disk('public')->delete($version->file_path);
        $pdfPath = str_replace('.docx', '.pdf', $version->file_path);
        Storage::disk('public')->delete($pdfPath);

        $version->delete();

        return redirect()->route('custom.profile.show')->with('success', 'CV version deleted.');
    }

    private function validateCvInput(Request $request, bool $forAnalysis = false): array
    {
        $currentYear = now()->year;

        $rules = [
            'about_me' => 'required|string|max:2000',
            'professional_headline' => 'nullable|string|max:120',
            'target_role' => 'nullable|string|max:120',
            'phone' => 'nullable|string|max:32',
            'location' => 'nullable|string|max:160',
            'skills' => 'required|array|min:1',
            'skills.*.name' => 'required|string|max:100',
            'skills.*.level' => 'nullable|string|max:40',
            'skills.*.category' => 'nullable|string|max:60',
            'experience' => 'nullable|array',
            'experience.*.title' => 'nullable|string|max:255',
            'experience.*.company' => 'nullable|string|max:255',
            'experience.*.location' => 'nullable|string|max:120',
            'experience.*.start_date' => 'nullable|date',
            'experience.*.end_date' => 'nullable|date',
            'experience.*.description' => 'nullable|string|max:2000',
            'experience.*.achievements' => 'nullable|array',
            'experience.*.achievements.*' => 'nullable|string|max:255',
            'education' => 'nullable|array',
            'education.*.degree' => 'nullable|string|max:255',
            'education.*.institution' => 'nullable|string|max:255',
            'education.*.completion_year' => 'nullable|integer|min:1900|max:' . ($currentYear + 1),
            'education.*.notes' => 'nullable|string|max:500',
            'certifications' => 'nullable|array',
            'certifications.*.name' => 'nullable|string|max:255',
            'certifications.*.issuer' => 'nullable|string|max:255',
            'certifications.*.year' => 'nullable|integer|min:1900|max:' . ($currentYear + 1),
            'projects' => 'nullable|array',
            'projects.*.name' => 'nullable|string|max:255',
            'projects.*.description' => 'nullable|string|max:1000',
            'projects.*.link' => 'nullable|url|max:255',
            'spoken_languages' => 'nullable|array',
            'spoken_languages.*.name' => 'nullable|string|max:80',
            'spoken_languages.*.level' => 'nullable|string|max:60',
            'interests' => 'nullable|array',
            'interests.*' => 'nullable|string|max:80',
            'social_links' => 'nullable|array|max:5',
            'social_links.*.label' => 'nullable|string|max:40',
            'social_links.*.url' => 'nullable|url|max:255',
            'references' => 'nullable|array|max:3',
            'references.*.name' => 'nullable|string|max:120',
            'references.*.contact' => 'nullable|string|max:160',
            'template' => ($forAnalysis ? 'sometimes' : 'required') . '|string|in:Minimal,Business,Tech',
            'notes' => 'nullable|string|max:255',
        ];

        $messages = [
            'education.*.completion_year.max' => 'Education year must not be later than :max.',
            'education.*.completion_year.min' => 'Education year must be at least :min.',
            'certifications.*.year.min' => 'Certification year must be at least :min.',
            'certifications.*.year.max' => 'Certification year must not be later than :max.',
            'projects.*.link.url' => 'Project link must be a valid URL (include https://).',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->setAttributeNames([
            'about_me' => 'professional summary',
            'professional_headline' => 'professional headline',
            'target_role' => 'target role',
            'phone' => 'phone number',
            'location' => 'location',
            'skills.*.name' => 'skill name',
            'skills.*.level' => 'skill level',
            'skills.*.category' => 'skill category',
            'experience.*.title' => 'experience title',
            'experience.*.company' => 'experience company',
            'experience.*.location' => 'experience location',
            'experience.*.start_date' => 'experience start date',
            'experience.*.end_date' => 'experience end date',
            'experience.*.description' => 'experience description',
            'experience.*.achievements.*' => 'experience achievement',
            'education.*.degree' => 'education degree',
            'education.*.institution' => 'education institution',
            'education.*.completion_year' => 'education year',
            'education.*.notes' => 'education notes',
            'certifications.*.name' => 'certification name',
            'certifications.*.issuer' => 'certification issuer',
            'certifications.*.year' => 'certification year',
            'projects.*.name' => 'project name',
            'projects.*.description' => 'project description',
            'projects.*.link' => 'project link',
            'spoken_languages.*.name' => 'language name',
            'spoken_languages.*.level' => 'language level',
            'interests.*' => 'interest',
            'social_links.*.label' => 'social link label',
            'social_links.*.url' => 'social link URL',
            'references.*.name' => 'reference name',
            'references.*.contact' => 'reference contact',
        ]);

        $validator->after(function ($validator) use ($request) {
            foreach ($request->input('experience', []) as $index => $role) {
                $start = Arr::get($role, 'start_date');
                $end = Arr::get($role, 'end_date');

                if ($start && $end) {
                    try {
                        if (Carbon::parse($end)->lt(Carbon::parse($start))) {
                            $validator->errors()->add("experience.$index.end_date", 'End date must be after the start date.');
                        }
                    } catch (\Throwable $e) {
                        $validator->errors()->add("experience.$index.end_date", 'Invalid date value.');
                    }
                }
            }
        });

        return $validator->validate();
    }

    private function prepareCvData(array $input, $user): array
    {
        $skills = $this->normaliseSkills($input['skills'] ?? [], $user?->skills ?? []);
        $experience = $this->normaliseExperience($input['experience'] ?? [], $user?->work_experience ?? []);
        $education = $this->normaliseEducation($input['education'] ?? [], $user?->education ?? []);
        $projects = $this->normaliseProjects($input['projects'] ?? []);
        $certifications = $this->normaliseCertifications($input['certifications'] ?? []);
        $languages = $this->normaliseLanguages($input['spoken_languages'] ?? []);
        $interests = $this->normaliseInterests($input['interests'] ?? []);
        $social = $this->normaliseSocialLinks($input['social_links'] ?? []);
        $references = $this->normaliseReferences($input['references'] ?? []);

        $headline = $this->cleanString($input['professional_headline'] ?? '')
            ?: $this->cleanString($input['target_role'] ?? '')
            ?: ($experience[0]['title'] ?? null);

        $summary = $this->cleanString($input['about_me'] ?? ($user?->summary ?? ''));

        $primaryLocation = $this->cleanString($input['location'] ?? ($user?->location ?? ''));
        $keywords = $this->extractKeywords($skills, $experience);

        return [
            'name' => $user?->name,
            'headline' => $headline,
            'email' => $user?->email,
            'phone' => $this->cleanString($input['phone'] ?? ($user?->phone ?? '')),
            'location' => $primaryLocation,
            'about' => $summary,
            'skills' => $skills,
            'experience' => $experience,
            'education' => $education,
            'projects' => $projects,
            'certifications' => $certifications,
            'languages' => $languages,
            'interests' => $interests,
            'social_links' => $social,
            'references' => $references,
            'meta' => [
                'generated_at' => now()->toIso8601String(),
                'language' => 'en',
                'template' => $input['template'] ?? 'Minimal',
                'keywords' => $keywords,
            ],
            'photo' => $user?->profile_photo_path,
        ];
    }

    private function normaliseSkills(array $requestSkills, $storedSkills): array
    {
        $collection = collect($requestSkills);

        if (is_array($storedSkills) && count($storedSkills) > 0) {
            $collection = $collection->concat($storedSkills);
        }

        return $collection
            ->filter(function ($skill) {
                $name = is_array($skill) ? ($skill['name'] ?? null) : $skill;
                return filled($this->cleanString($name));
            })
            ->map(function ($skill) {
                if (is_array($skill)) {
                    $name = $this->cleanString($skill['name'] ?? '');
                    $level = $this->cleanString($skill['level'] ?? '');
                    $category = $this->cleanString($skill['category'] ?? '');
                } else {
                    $name = $this->cleanString($skill);
                    $level = null;
                    $category = null;
                }

                return [
                    'name' => $name ? Str::title($name) : null,
                    'level' => $level,
                    'category' => $category ? Str::title($category) : null,
                ];
            })
            ->filter(fn ($skill) => filled($skill['name']))
            ->unique(fn ($skill) => Str::lower($skill['name']))
            ->values()
            ->all();
    }

    private function normaliseExperience(array $requestExperience, $storedExperience): array
    {
        $current = collect($requestExperience)
            ->map(fn ($item) => $this->mapExperienceEntry($item));

        if (is_array($storedExperience) && count($storedExperience) > 0) {
            $legacy = collect($storedExperience)
                ->map(fn ($item) => $this->parseLegacyExperienceEntry($item));
            $current = $current->concat($legacy);
        }

        return $current
            ->filter()
            ->sortByDesc(fn ($item) => $item['start_date'] ?? '0000-00-00')
            ->values()
            ->all();
    }

    private function normaliseEducation(array $requestEducation, $storedEducation): array
    {
        $collection = collect($requestEducation);

        if (is_array($storedEducation) && count($storedEducation) > 0) {
            $collection = $collection->concat($storedEducation);
        }

        return $collection
            ->map(function ($item) {
                if (!is_array($item)) {
                    return null;
                }

                $degree = $this->cleanString($item['degree'] ?? ($item['title'] ?? ''));
                $institution = $this->cleanString($item['institution'] ?? ($item['school'] ?? ''));
                $year = $item['completion_year'] ?? ($item['year'] ?? null);

                if (!$degree && !$institution) {
                    return null;
                }

                return [
                    'degree' => $degree ? Str::title($degree) : null,
                    'institution' => $institution ? Str::title($institution) : null,
                    'completion_year' => $year ? (int) $year : null,
                    'notes' => $this->cleanString($item['notes'] ?? ''),
                ];
            })
            ->filter()
            ->unique(fn ($item) => Str::lower(($item['degree'] ?? '') . ($item['institution'] ?? '')))
            ->values()
            ->all();
    }

    private function normaliseCertifications(array $items): array
    {
        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(function ($item) {
                $name = $this->cleanString($item['name'] ?? '');
                $issuer = $this->cleanString($item['issuer'] ?? '');
                $year = $item['year'] ?? null;

                if (!$name) {
                    return null;
                }

                return [
                    'name' => Str::title($name),
                    'issuer' => $issuer ? Str::title($issuer) : null,
                    'year' => $year ? (int) $year : null,
                ];
            })
            ->filter()
            ->unique(fn ($item) => Str::lower($item['name']))
            ->values()
            ->all();
    }

    private function normaliseProjects(array $items): array
    {
        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(function ($item) {
                $name = $this->cleanString($item['name'] ?? '');
                $description = $this->cleanString($item['description'] ?? '');
                $link = $this->cleanString($item['link'] ?? '');

                if (!$name && !$description) {
                    return null;
                }

                return [
                    'name' => $name ? Str::title($name) : null,
                    'description' => $description,
                    'link' => $link,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function normaliseLanguages(array $items): array
    {
        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(function ($item) {
                $name = $this->cleanString($item['name'] ?? '');
                $level = $this->cleanString($item['level'] ?? '');

                if (!$name) {
                    return null;
                }

                return [
                    'name' => Str::title($name),
                    'level' => $level,
                ];
            })
            ->filter()
            ->unique(fn ($item) => Str::lower($item['name']))
            ->values()
            ->all();
    }

    private function normaliseInterests(array $items): array
    {
        return collect($items)
            ->map(fn ($item) => $this->cleanString(is_array($item) ? ($item['name'] ?? '') : $item))
            ->filter()
            ->unique(fn ($item) => Str::lower($item))
            ->values()
            ->all();
    }

    private function normaliseSocialLinks(array $items): array
    {
        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(function ($item) {
                $label = $this->cleanString($item['label'] ?? '');
                $url = $this->cleanString($item['url'] ?? '');

                if (!$label && !$url) {
                    return null;
                }

                return [
                    'label' => $label ? Str::title($label) : null,
                    'url' => $url,
                ];
            })
            ->filter(fn ($item) => filled($item['url']))
            ->unique(fn ($item) => Str::lower($item['url']))
            ->values()
            ->all();
    }

    private function normaliseReferences(array $items): array
    {
        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(function ($item) {
                $name = $this->cleanString($item['name'] ?? '');
                $contact = $this->cleanString($item['contact'] ?? '');

                if (!$name && !$contact) {
                    return null;
                }

                return [
                    'name' => $name ? Str::title($name) : null,
                    'contact' => $contact,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function mapExperienceEntry($item): ?array
    {
        if (!is_array($item)) {
            return null;
        }

        $title = $this->cleanString($item['title'] ?? '');
        $company = $this->cleanString($item['company'] ?? '');
        $location = $this->cleanString($item['location'] ?? '');
        $description = $this->cleanString($item['description'] ?? '');

        if (!$title && !$company && !$description) {
            return null;
        }

        $startDate = $this->parseNullableDate($item['start_date'] ?? null);
        $endDate = $this->parseNullableDate($item['end_date'] ?? null);

        $achievements = $this->normaliseAchievements($item['achievements'] ?? null, $description);

        return [
            'title' => $title ? Str::title($title) : null,
            'company' => $company ? Str::title($company) : null,
            'location' => $location ? Str::title($location) : null,
            'start_date' => $startDate?->toDateString(),
            'end_date' => $endDate?->toDateString(),
            'tenure_months' => $this->calculateTenureMonths($startDate, $endDate),
            'achievements' => $achievements,
            'description' => $description,
        ];
    }

    private function parseLegacyExperienceEntry($item): ?array
    {
        if (!is_array($item)) {
            return null;
        }

        $title = $this->cleanString($item['title'] ?? '');
        $company = $this->cleanString($item['company'] ?? '');
        $description = $this->cleanString($item['description'] ?? '');

        if (!$title && !$company && !$description) {
            return null;
        }

        [$startDate, $endDate] = $this->parseYearsRange($item['years'] ?? '');

        return [
            'title' => $title ? Str::title($title) : null,
            'company' => $company ? Str::title($company) : null,
            'location' => null,
            'start_date' => $startDate?->toDateString(),
            'end_date' => $endDate?->toDateString(),
            'tenure_months' => $this->calculateTenureMonths($startDate, $endDate),
            'achievements' => $this->splitDescriptionIntoBullets($description),
            'description' => $description,
        ];
    }

    private function normaliseAchievements($achievements, ?string $fallbackDescription): array
    {
        $items = collect($achievements)
            ->map(fn ($item) => $this->cleanString(is_array($item) ? ($item['text'] ?? '') : $item))
            ->filter();

        if ($items->isEmpty() && $fallbackDescription) {
            $items = collect($this->splitDescriptionIntoBullets($fallbackDescription));
        }

        return $items
            ->unique(fn ($item) => Str::lower($item))
            ->values()
            ->all();
    }

    private function splitDescriptionIntoBullets(?string $description): array
    {
        if (!$description) {
            return [];
        }

        $parts = preg_split('/[\r\n]+|\.\s+/u', $description) ?: [];

        return collect($parts)
            ->map(fn ($part) => $this->cleanString($part))
            ->filter(fn ($part) => filled($part) && Str::length($part) > 3)
            ->values()
            ->all();
    }

    private function parseNullableDate($value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function parseYearsRange(?string $years): array
    {
        if (!$years) {
            return [null, null];
        }

        if (!preg_match('/(?P<start>\d{4})(?:\s*[\-–]\s*(?P<end>(?:\d{4}|present|now|dabar)))?/i', $years, $matches)) {
            return [null, null];
        }

        $start = isset($matches['start']) ? Carbon::createFromDate((int) $matches['start'], 1, 1) : null;
        $endRaw = $matches['end'] ?? null;
        $end = null;

        if ($endRaw) {
            $endRaw = Str::lower($endRaw);
            if (in_array($endRaw, ['present', 'now', 'dabar'])) {
                $end = null;
            } else {
                $end = Carbon::createFromDate((int) $endRaw, 1, 1);
            }
        }

        return [$start, $end];
    }

    private function calculateTenureMonths(?Carbon $start, ?Carbon $end): ?int
    {
        if (!$start) {
            return null;
        }

        $endDate = $end ?? Carbon::now();

        return $endDate->diffInMonths($start);
    }

    private function extractKeywords(array $skills, array $experience): array
    {
        $pool = collect($skills)->pluck('name')
            ->merge(collect($experience)->flatMap(fn ($role) => $role['achievements'] ?? []))
            ->implode(' ');

        $words = collect(preg_split('/[^\p{L}\p{N}\+\#]+/u', Str::lower($pool)))
            ->filter(fn ($word) => Str::length($word) >= 3)
            ->reject(fn ($word) => in_array($word, ['and', 'the']))
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(12)
            ->values()
            ->all();

        return $words;
    }

    private function cleanString($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim(strip_tags((string) $value));

        return $trimmed === '' ? null : $trimmed;
    }
}
