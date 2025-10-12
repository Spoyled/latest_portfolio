<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AtsCheckerService
{
    private array $actionVerbs = [
        'built', 'created', 'developed', 'deployed', 'designed', 'implemented', 'delivered', 'improved', 'reduced',
        'analysed', 'analyzed', 'led', 'orchestrated', 'accelerated', 'boosted', 'enhanced', 'resolved', 'streamlined',
        'automated', 'mentored', 'negotiated', 'launched', 'measured', 'collaborated', 'architected', 'optimised', 'optimized',
    ];

    private array $hardSkillHints = [
        'php', 'laravel', 'javascript', 'typescript', 'symfony', 'react', 'vue', 'sql', 'mysql', 'postgres', 'docker',
        'kubernetes', 'aws', 'azure', 'gcp', 'git', 'linux', 'rest', 'api', 'microservices', 'tailwind', 'unit test',
        'integration test', 'ci/cd', 'terraform', 'redis', 'rabbitmq', 'elastic', 'oop', 'design pattern', 'testing',
    ];

    private array $softSkillHints = [
        'leadership', 'communication', 'collaboration', 'mentoring', 'teamwork', 'problem solving', 'ownership',
        'stakeholder', 'adaptability', 'critical thinking', 'coaching', 'analytical', 'presentation', 'conflict resolution',
    ];

    public function analyze(array $cvData): array
    {
        $categories = [
            [
                'label' => 'Profile',
                'weight' => 20,
                'result' => $this->evaluateProfile($cvData, 20),
            ],
            [
                'label' => 'Summary',
                'weight' => 15,
                'result' => $this->evaluateSummary($cvData, 15),
            ],
            [
                'label' => 'Skills',
                'weight' => 20,
                'result' => $this->evaluateSkills($cvData, 20),
            ],
            [
                'label' => 'Experience',
                'weight' => 25,
                'result' => $this->evaluateExperience($cvData, 25),
            ],
            [
                'label' => 'Education',
                'weight' => 10,
                'result' => $this->evaluateEducation($cvData, 10),
            ],
            [
                'label' => 'Structure',
                'weight' => 10,
                'result' => $this->evaluateStructure($cvData, 10),
            ],
        ];

        $rawScore = 0;
        $errors = [];
        $warnings = [];
        $highlights = [];
        $autoFixes = [];
        $breakdown = [];

        foreach ($categories as $category) {
            $result = $category['result'];
            $rawScore += $result['score'];
            $errors = array_merge($errors, $result['errors']);
            $warnings = array_merge($warnings, $result['warnings']);
            $highlights = array_merge($highlights, $result['highlights']);

            foreach ($result['autoFixes'] as $path => $value) {
                $autoFixes[$path] = $value;
            }

            $breakdown[] = [
                'category' => $category['label'],
                'score' => round($result['score']),
                'weight' => $category['weight'],
                'notes' => $result['notes'],
            ];
        }

        $atsScore = max(0, min(100, (int) round($rawScore)));

        return [
            'atsScore' => $atsScore,
            'errors' => array_values($errors),
            'warnings' => array_values($warnings),
            'highlights' => array_values($highlights),
            'autoFixes' => $autoFixes,
            'scoreBreakdown' => $breakdown,
        ];
    }

    public function applyFixes(array $cvData, array $fixes): array
    {
        foreach ($fixes as $path => $value) {
            Arr::set($cvData, $path, $value);
        }

        return $cvData;
    }

    private function evaluateProfile(array $cvData, int $weight): array
    {
        $score = $weight;
        $errors = [];
        $warnings = [];
        $highlights = [];
        $autoFixes = [];
        $notes = [];

        $name = trim((string) ($cvData['name'] ?? ''));
        if ($name === '') {
            $errors[] = ['section' => 'profile', 'message' => 'Full name is missing.', 'weight' => 8];
            $score -= 8;
        } else {
            $notes[] = 'Candidate name is clearly presented.';
        }

        $email = trim((string) ($cvData['email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = ['section' => 'profile', 'message' => 'Email address is missing or invalid.', 'weight' => 8];
            $score -= 8;
        } else {
            $notes[] = 'Professional email provided.';
        }

        $rawPhone = trim((string) ($cvData['phone'] ?? ''));
        $digits = preg_replace('/\D+/', '', $rawPhone);
        if (!$digits || Str::length($digits) < 8) {
            $warnings[] = ['section' => 'profile', 'message' => 'Add a phone number in international format (+370...).'];
            $score -= 4;
        } else {
            $notes[] = 'Phone number is available.';
        }

        $location = trim((string) ($cvData['location'] ?? ''));
        if ($location === '') {
            $warnings[] = ['section' => 'profile', 'message' => 'Specify a city or relocation preference.'];
            $score -= 3;
        } else {
            $notes[] = 'Location is specified.';
        }

        $socialLinks = collect($cvData['social_links'] ?? []);
        if ($socialLinks->isNotEmpty()) {
            $labels = $socialLinks->pluck('label')->filter()->map(fn ($label) => Str::lower($label));
            if ($labels->contains(fn ($label) => Str::contains($label, 'linkedin'))) {
                $highlights[] = ['section' => 'profile', 'message' => 'LinkedIn profile is included.'];
            }
            if ($labels->contains(fn ($label) => Str::contains($label, ['github', 'gitlab']))) {
                $highlights[] = ['section' => 'profile', 'message' => 'Engineering portfolio link is provided.'];
            }
        }

        return $this->resultPayload(
            $score,
            $errors,
            $warnings,
            $highlights,
            $autoFixes,
            implode(' ', $notes) ?: 'Complete the contact details to reach a 20/20 score.'
        );
    }

    private function evaluateSummary(array $cvData, int $weight): array
    {
        $score = $weight;
        $errors = [];
        $warnings = [];
        $highlights = [];
        $autoFixes = [];

        $summary = trim((string) ($cvData['about'] ?? ''));
        if ($summary === '') {
            $errors[] = ['section' => 'summary', 'message' => 'Professional summary is missing.', 'weight' => 12];
            return $this->resultPayload(0, $errors, $warnings, $highlights, $autoFixes, 'Add a 3-4 sentence summary to help ATS identify your focus.');
        }

        $wordCount = str_word_count($summary);
        if ($wordCount < 40) {
            $warnings[] = ['section' => 'summary', 'message' => 'Aim for at least 40-60 words describing impact and focus.'];
            $score -= 5;
        } elseif ($wordCount > 220) {
            $warnings[] = ['section' => 'summary', 'message' => 'Summary is too long – keep it within four concise sentences.'];
            $score -= 4;
            $autoFixes['about'] = Str::of($summary)->limit(1200, '...')->toString();
        }

        if (!$this->containsActionVerb($summary)) {
            $warnings[] = ['section' => 'summary', 'message' => 'Start sentences with action verbs (e.g. “Lead”, “Optimised”).'];
            $score -= 3;
        }

        if (!preg_match('/\d+[%\+kK€$]?/', $summary)) {
            $warnings[] = ['section' => 'summary', 'message' => 'Add at least one measurable result (e.g. “increased conversion by 18%”).'];
            $score -= 3;
        }

        $keywords = collect(Arr::get($cvData, 'meta.keywords', []));
        if ($keywords->isNotEmpty()) {
            $matched = $keywords->filter(fn ($keyword) => Str::contains(Str::lower($summary), Str::lower($keyword)));
            if ($matched->count() >= 3) {
                $highlights[] = ['section' => 'summary', 'message' => 'Summary covers key keywords: ' . $matched->take(3)->implode(', ') . '.'];
            } else {
                $warnings[] = ['section' => 'summary', 'message' => 'Embed role-specific keywords such as ' . $keywords->take(5)->implode(', ') . '.'];
                $score -= 2;
            }
        }

        if (!Str::endsWith($summary, ['.', '!', '?'])) {
            $autoFixes['about'] = rtrim($summary, '.') . '.';
        }

        return $this->resultPayload(
            $score,
            $errors,
            $warnings,
            $highlights,
            $autoFixes,
            'Great summaries balance mission, strengths, and measurable outcomes.'
        );
    }

    private function evaluateSkills(array $cvData, int $weight): array
    {
        $score = $weight;
        $errors = [];
        $warnings = [];
        $highlights = [];
        $autoFixes = [];

        $skills = collect($cvData['skills'] ?? []);
        if ($skills->isEmpty()) {
            $errors[] = ['section' => 'skills', 'message' => 'Add a skills section with core technologies.', 'weight' => 15];
            return $this->resultPayload(0, $errors, $warnings, $highlights, $autoFixes, 'ATS looks for keywords in the skills list.');
        }

        if ($skills->count() < 5) {
            $warnings[] = ['section' => 'skills', 'message' => 'List at least 6–8 relevant skills.'];
            $score -= 6;
        }

        $uniqueSkills = $skills->unique(fn ($skill) => Str::lower((string) ($skill['name'] ?? '')))->values();
        if ($uniqueSkills->count() !== $skills->count()) {
            $autoFixes['skills'] = $uniqueSkills->all();
            $warnings[] = ['section' => 'skills', 'message' => 'Duplicate skills detected – they will be merged.'];
        }

        $names = $uniqueSkills->pluck('name')->filter()->map(fn ($name) => Str::lower($name));
        $hardMatches = $this->countMatches($names, $this->hardSkillHints);
        $softMatches = $this->countMatches($names, $this->softSkillHints);

        if ($hardMatches < 3) {
            $warnings[] = ['section' => 'skills', 'message' => 'Include more technical skills (Laravel, Docker, PHPUnit, CI/CD).'];
            $score -= 4;
        }

        if ($softMatches < 2) {
            $warnings[] = ['section' => 'skills', 'message' => 'Add soft skills that show collaboration or leadership.'];
            $score -= 2;
        }

        if ($hardMatches >= 5) {
            $highlights[] = ['section' => 'skills', 'message' => 'Strong technical stack coverage.'];
        }

        return $this->resultPayload(
            $score,
            $errors,
            $warnings,
            $highlights,
            $autoFixes,
            'Group skills by category (backend, tooling, cloud) for extra clarity.'
        );
    }

    private function evaluateExperience(array $cvData, int $weight): array
    {
        $score = $weight;
        $errors = [];
        $warnings = [];
        $highlights = [];
        $autoFixes = [];

        $experience = collect($cvData['experience'] ?? []);
        if ($experience->isEmpty()) {
            $errors[] = ['section' => 'experience', 'message' => 'Work experience section is empty.', 'weight' => 20];
            return $this->resultPayload(0, $errors, $warnings, $highlights, $autoFixes, 'Include at least one role with accomplishments.');
        }

        $achievementTotals = 0;
        $quantifiableTotals = 0;

        foreach ($experience as $index => $role) {
            $title = trim((string) Arr::get($role, 'title'));
            $company = trim((string) Arr::get($role, 'company'));
            $description = trim((string) Arr::get($role, 'description'));
            $achievements = collect(Arr::get($role, 'achievements', []))->filter();

            if ($title === '' || $company === '') {
                $errors[] = ['section' => 'experience', 'index' => $index, 'message' => 'Specify both job title and company for each role.', 'weight' => 6];
                $score -= 6;
            }

            if ($achievements->isEmpty() && $description === '') {
                $errors[] = ['section' => 'experience', 'index' => $index, 'message' => 'Add accomplishments or project scope for this role.', 'weight' => 4];
                $score -= 4;
            }

            $achievementTotals += $achievements->count();
            $quantifiableTotals += $this->countQuantifiableMetrics($achievements->all());

            if ($achievements->count() < 3) {
                $warnings[] = ['section' => 'experience', 'index' => $index, 'message' => 'Include 3–5 bullet points per role.'];
                $score -= 2;
            }

            if (!$this->roleHasActionVerb($achievements->all(), $description)) {
                $warnings[] = ['section' => 'experience', 'index' => $index, 'message' => 'Start bullet points with strong action verbs.'];
                $score -= 2;
            }

            $startDate = $this->parseDate(Arr::get($role, 'start_date'));
            if (!$startDate) {
                $warnings[] = ['section' => 'experience', 'index' => $index, 'message' => 'Add a start date for this role (YYYY-MM-DD).'];
                $score -= 3;
            }
        }

        if ($quantifiableTotals === 0) {
            $warnings[] = ['section' => 'experience', 'message' => 'Add quantifiable results in at least one position.'];
            $score -= 5;
        } else {
            $highlights[] = ['section' => 'experience', 'message' => 'Quantifiable impact is visible in work history.'];
        }

        if ($achievementTotals >= $experience->count() * 3) {
            $highlights[] = ['section' => 'experience', 'message' => 'Roles include strong, well-structured bullet points.'];
        }

        return $this->resultPayload(
            $score,
            $errors,
            $warnings,
            $highlights,
            $autoFixes,
            'Focus bullets on metrics, ownership, and outcomes.'
        );
    }

    private function evaluateEducation(array $cvData, int $weight): array
    {
        $score = $weight;
        $errors = [];
        $warnings = [];
        $highlights = [];
        $autoFixes = [];

        $education = collect($cvData['education'] ?? []);
        if ($education->isEmpty()) {
            $errors[] = ['section' => 'education', 'message' => 'Provide at least one education entry.', 'weight' => 8];
            return $this->resultPayload(0, $errors, $warnings, $highlights, $autoFixes, 'List the most relevant degree or certification.');
        }

        if ($education->count() >= 2) {
            $highlights[] = ['section' => 'education', 'message' => 'Multiple education entries show continuous learning.'];
        }

        if ($education->contains(fn ($item) => empty($item['degree']) || empty($item['institution']))) {
            $warnings[] = ['section' => 'education', 'message' => 'State the programme and institution for each entry.'];
            $score -= 3;
        }

        return $this->resultPayload(
            $score,
            $errors,
            $warnings,
            $highlights,
            $autoFixes,
            'Highlight your most recent or most relevant studies.'
        );
    }

    private function evaluateStructure(array $cvData, int $weight): array
    {
        $score = $weight;
        $errors = [];
        $warnings = [];
        $highlights = [];
        $autoFixes = [];

        $sections = collect([
            'projects' => collect($cvData['projects'] ?? []),
            'certifications' => collect($cvData['certifications'] ?? []),
            'languages' => collect($cvData['languages'] ?? []),
            'interests' => collect($cvData['interests'] ?? []),
            'references' => collect($cvData['references'] ?? []),
        ]);

        $richSections = $sections->filter(fn ($items) => $items->isNotEmpty())->count();
        if ($richSections === 0) {
            $warnings[] = ['section' => 'structure', 'message' => 'Add an extra section (projects, certifications, languages, etc.).'];
            $score -= 4;
        } elseif ($richSections >= 2) {
            $highlights[] = ['section' => 'structure', 'message' => 'Supporting sections provide a fuller picture.'];
        }

        $keywords = collect(Arr::get($cvData, 'meta.keywords', []));
        if ($keywords->isEmpty()) {
            $warnings[] = ['section' => 'structure', 'message' => 'Include relevant keywords across the document.'];
            $score -= 3;
        }

        $summaryLength = Str::length((string) ($cvData['about'] ?? ''));
        if ($summaryLength > 1500) {
            $warnings[] = ['section' => 'structure', 'message' => 'Summary exceeds the recommended 1500 characters.'];
            $score -= 3;
        }

        return $this->resultPayload(
            $score,
            $errors,
            $warnings,
            $highlights,
            $autoFixes,
            'Balance between concise sections and rich supporting details.'
        );
    }

    private function resultPayload(
        float $score,
        array $errors,
        array $warnings,
        array $highlights,
        array $autoFixes,
        string $notes
    ): array {
        return [
            'score' => max(0, $score),
            'errors' => $errors,
            'warnings' => $warnings,
            'highlights' => $highlights,
            'autoFixes' => $autoFixes,
            'notes' => $notes,
        ];
    }

    private function containsActionVerb(string $text): bool
    {
        $lower = Str::lower($text);

        foreach ($this->actionVerbs as $verb) {
            if (Str::contains($lower, $verb)) {
                return true;
            }
        }

        return false;
    }

    private function roleHasActionVerb(array $achievements, string $fallback): bool
    {
        $text = Str::lower(implode(' ', $achievements) . ' ' . $fallback);
        foreach ($this->actionVerbs as $verb) {
            if (Str::contains($text, $verb)) {
                return true;
            }
        }

        return false;
    }

    private function countMatches($skills, array $dictionary): int
    {
        $skills = collect($skills);

        return collect($dictionary)
            ->filter(fn ($item) => $skills->contains(fn ($skill) => Str::contains($skill, $item)))
            ->count();
    }

    private function countQuantifiableMetrics(array $achievements): int
    {
        $count = 0;

        foreach ($achievements as $line) {
            if (preg_match('/\d+/', $line) || Str::contains($line, ['%', '€', '$', '+'])) {
                $count++;
            }
        }

        return $count;
    }

    private function parseDate($value): ?Carbon
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
}
