<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpWord\PhpWord;

class CustomProfileController extends Controller
{
    public function show()
    {
        $user = Auth::guard('employer')->check() ? Auth::guard('employer')->user() : Auth::user();

        if (Auth::guard('employer')->check()) {
            // Gather statistics for employers
            $totalJobPosts = $user->posts->count();
            $activeJobPosts = $user->posts()->where('is_active', true)->count();
            $applicationsReceived = $user->posts()->withCount('applicants')->get()->sum('applicants_count');

            return view('profile.custom', compact('user', 'totalJobPosts', 'activeJobPosts', 'applicationsReceived'));
        }

        // For regular users
        return view('profile.custom', compact('user'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|max:2048', // Max size 2MB
            'password' => 'nullable|string|min:8|confirmed', // Validate password only if provided
            'company_description' => 'nullable|string|max:1000', // Optional field for company description
        ]);

        $user = Auth::guard('employer')->check() ? Auth::guard('employer')->user() : Auth::user();

        // Update profile picture
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('public/profile_photos');
            $user->profile_photo_path = basename($path);
        }

        // Update name
        $user->name = $request->name;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update company description if provided
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
        $user = Auth::user();
        $phpWord = new PhpWord();

        // Set default styles
        $phpWord->getDefaultFontName('Arial');
        $phpWord->getDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginLeft' => 1200,
            'marginRight' => 1200,
            'marginTop' => 1200,
            'marginBottom' => 1200,
        ]);

        // Add title
        $section->addText(
            "{$user->name}'s CV",
            ['size' => 18, 'bold' => true],
            ['alignment' => 'center']
        );

        $section->addTextBreak(2);

        // Add profile picture if exists
        if ($user->profile_photo_path) {
            $photoPath = storage_path('app/public/profile_photos/' . $user->profile_photo_path);
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

        // Add personal information
        $section->addText("Name: " . $user->name, ['bold' => true]);
        $section->addText("Email: " . $user->email, ['bold' => true]);
        $section->addTextBreak(2);

        // Add About Me section
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

        // Add Work Experience section
        $section->addText(
            "Work Experience",
            ['bold' => true, 'size' => 14],
            ['alignment' => 'left']
        );
        $section->addText(
            $request->work_experience ?: "No work experience added.",
            ['italic' => true],
            ['alignment' => 'left']
        );
        $section->addTextBreak(1);

        // Add Skills section
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

        // Save Word file
        $fileName = 'CV_' . $user->id . '.docx';
        $filePath = storage_path("app/public/cvs/{$fileName}");

        // Ensure the directory exists
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0775, true);
        }

        $phpWord->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
