<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth('employer')->check() ? auth('employer')->user() : auth()->user();
        return view('profile.show', ['user' => $user]);
    }

    public function generateCv()
    {
        $user = auth()->user();
        $phpWord = new PhpWord();

        // Set default font
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection();

        // --- Header ---
        $headerTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $headerTable->addRow();
        $textCell = $headerTable->addCell(8000);
        $textCell->addText($user->name, ['bold' => true, 'size' => 22]);
        if($user->location) {
            $textCell->addText($user->location);
        }
        $textCell->addText($user->email);


        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            $imageCell = $headerTable->addCell(2000);
            $imageCell->addImage(
                storage_path('app/public/' . $user->profile_photo_path),
                ['width' => 60, 'height' => 60, 'alignment' => Jc::END]
            );
        }
        $section->addTextBreak(1);


        // --- Summary/Objective ---
        if ($user->summary) {
            $section->addTitle('Summary', 1);
            $section->addText($user->summary, [], ['spaceAfter' => 240]);
        }

        // --- Skills ---
        if ($user->skills && is_array($user->skills) && count($user->skills) > 0) {
            $section->addTitle('Skills', 1);
            foreach ($user->skills as $skill) {
                $section->addListItem($skill, 0);
            }
            $section->addTextBreak(1);
        }

        // --- Work Experience ---
        if ($user->work_experience && is_array($user->work_experience) && count($user->work_experience) > 0) {
            $section->addTitle('Work Experience', 1);
            foreach ($user->work_experience as $job) {
                $section->addText($job['title'] . ' at ' . $job['company'], ['bold' => true]);
                $section->addText($job['years']);
                $section->addText($job['description']);
                $section->addTextBreak(1);
            }
        }

        // --- Education ---
        if ($user->education && is_array($user->education) && count($user->education) > 0) {
            $section->addTitle('Education', 1);
            foreach ($user->education as $edu) {
                $section->addText($edu['degree'] . ' at ' . $edu['institution'], ['bold' => true]);
                $section->addText($edu['years']);
            }
        }

        // --- Save and Download ---
        $filename = 'cv_' . $user->id . '.docx';
        $path = 'cv/' . $filename;
        Storage::disk('public')->makeDirectory('cv');
        
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path('app/public/' . $path));

        return Storage::disk('public')->download($path);
    }
}
