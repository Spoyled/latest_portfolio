<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\Validator;


class CustomProfileController extends Controller
{
    public function show()
    {
        $user = Auth::guard('employer')->check() ? Auth::guard('employer')->user() : Auth::user();

        if (Auth::guard('employer')->check()) {
            $totalJobPosts = $user->posts->count();
            $activeJobPosts = $user->posts()->where('is_active', true)->count();
            $closedJobPosts = $user->posts()->whereNotNull('closed_at')->count();
            $applicationsReceived = $user->posts()->withCount('applicants')->get()->sum('applicants_count');

            return view('profile.custom', compact(
                'user',
                'totalJobPosts',
                'activeJobPosts',
                'closedJobPosts',
                'applicationsReceived'
            ));
        }

        $appliedPosts = $user->appliedPosts()->latest()->get();
        return view('profile.custom', compact('user', 'appliedPosts'));
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

        // Update profile picture
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

        $path = $request->file('cv_file')->store('public/cvs');
        $user = Auth::user();
        $user->cv_path = basename($path);
        $user->save();

        return redirect()->route('custom.profile.show')->with('success', 'CV uploaded successfully.');
    }

    public function generateCV(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'about_me' => 'required|string|max:1000',
            'skills' => 'required|string|max:500',
            'education' => 'nullable|string|max:500',
        
            'job_title' => 'nullable|array',
            'job_title.*' => 'nullable|string|max:255',
            'company' => 'nullable|array',
            'company.*' => 'nullable|string|max:255',
            'duration' => 'nullable|array',
            'duration.*' => 'nullable|string|max:255',
            'job_description' => 'nullable|array',
            'job_description.*' => 'nullable|string|max:1000',
        ]);
        
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $phpWord = new PhpWord();

        $phpWord->getDefaultFontName('Arial');
        $phpWord->getDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginLeft' => 1200,
            'marginRight' => 1200,
            'marginTop' => 1200,
            'marginBottom' => 1200,
        ]);

        $section->addText(
            "{$user->name}'s CV",
            ['size' => 18, 'bold' => true],
            ['alignment' => 'center']
        );

        $section->addTextBreak(2);

        if ($user->profile_photo_path) {
            $photoPath = storage_path("app/public/profile_photos/{$user->profile_photo_path}");
            if (file_exists($photoPath)) {
                $section->addImage(
                    $photoPath,
                    [
                        'width' => 100,
                        'height' => 100,
                        'alignment' => 'center',
                    ]
                );
                $section->addTextBreak(1);
            }
        }

        $section->addText("Name: " . $user->name, ['bold' => true]);
        $section->addText("Email: " . $user->email, ['bold' => true]);
        $section->addTextBreak(2);

       
        $section->addText(
            "About Me",
            ['bold' => true, 'size' => 14],
            ['alignment' => 'left']
        );
        $section->addText(
            $request->about_me ?: "N/A",
            ['italic' => true],
            ['alignment' => 'left']
        );
        $section->addTextBreak(1);

        $section->addText("Work Experience", ['bold' => true, 'size' => 14]);

        $titles = $request->input('job_title', []);
        $companies = $request->input('company', []);
        $durations = $request->input('duration', []);
        $descriptions = $request->input('job_description', []);

        if (!empty($titles)) {
            foreach ($titles as $index => $title) {
                if (trim($title) || trim($companies[$index] ?? '') || trim($durations[$index] ?? '') || trim($descriptions[$index] ?? '')) {
                    $section->addText("Job #" . ($index + 1), ['bold' => true]);
                    $section->addText("Job Title: " . ($title ?: 'N/A'));
                    $section->addText("Company: " . ($companies[$index] ?? 'N/A'));
                    $section->addText("Duration: " . ($durations[$index] ?? 'N/A'));
                    $section->addText("Description: " . ($descriptions[$index] ?? 'N/A'));
                    $section->addTextBreak(1);
                }
            }
        } else {
            $section->addText("No work experience added.");
        }

        $section->addTextBreak(1);

        $section->addText("Education", ['bold' => true, 'size' => 14]);
        $section->addText($request->education ?: "No education details provided.");
        $section->addTextBreak(1);


    
        $section->addText(
            "Skills",
            ['bold' => true, 'size' => 14],
            ['alignment' => 'left']
        );
        $section->addText(
            $request->skills ?: "No skills added.",
            ['italic' => true],
            ['alignment' => 'left']
        );

      
        $fileName = 'CV_' . $user->id . '_' . now()->format('Ymd_His') . '.docx';

        $filePath = storage_path("app/public/cvs/{$fileName}");

       
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0775, true);
        }

        $phpWord->save($filePath);

        // Store the file name in the user's cv_path
        $user->cv_path = 'cvs/' . $fileName;


        $user->save();

        return response()->download($filePath);
    }
}
