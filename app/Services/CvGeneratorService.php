<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Language;

class CvGeneratorService
{
    public function generate(array $cvData, string $template): string
    {
        $cvData = $this->prepareForTemplate($cvData);

        Storage::disk('public')->makeDirectory('cvs');
        $fileName     = 'cv_' . now()->format('Ymd_His') . '_' . Str::random(4) . '.docx';
        $relativePath = 'cvs/' . $fileName;
        $targetPath   = Storage::disk('public')->path($relativePath);

        // Try requested template first, then fall back to safe ones
        $order = match ($template) {
            'Tech'     => ['Tech', 'Business', 'Minimal'],
            'Business' => ['Business', 'Minimal'],
            default    => ['Minimal', 'Business'],
        };

        $lastError = null;

        foreach ($order as $tpl) {
            try {
                $phpWord = $this->buildDocument($cvData, $tpl);

                $tmp = tempnam(sys_get_temp_dir(), 'cv_') . '.docx';
                IOFactory::createWriter($phpWord, 'Word2007')->save($tmp);

                if ($this->validateDocx($tmp)) {
                    // atomically move the valid file into place
                    if (!@rename($tmp, $targetPath)) {
                        @copy($tmp, $targetPath);
                        @unlink($tmp);
                    }
                    clearstatcache(true, $targetPath);

                    return $relativePath; // always returns a file Word can open
                }

                $lastError = "Validation failed for template {$tpl}";
                @unlink($tmp);
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
                // try next template
            }
        }

        throw new \RuntimeException('All templates produced an invalid DOCX. Last error: ' . $lastError);
    }

    private function buildDocument(array $cvData, string $template): PhpWord
    {
        $phpWord = $this->initialiseDocument();

        switch ($template) {
            case 'Business':
                $this->renderBusinessTemplate($phpWord, $cvData);
                break;
            case 'Tech':
                $this->renderTechTemplate($phpWord, $cvData);
                break;
            case 'Minimal':
            default:
                $this->renderMinimalTemplate($phpWord, $cvData);
                break;
        }

        return $phpWord;
    }

    private function validateDocx(string $path): bool
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) return false;

        // Only the truly mandatory parts
        foreach (['[Content_Types].xml', 'word/document.xml'] as $required) {
            if ($zip->locateName($required) === false) {
                $zip->close();
                return false;
            }
        }

        // Basic XML well-formedness on required + common optional parts (if they exist)
        $toCheck = [
            'word/document.xml',
            'word/styles.xml',
            'word/numbering.xml',
            'word/_rels/document.xml.rels', // OPTIONAL – check only if present
        ];

        libxml_use_internal_errors(true);
        foreach ($toCheck as $xmlFile) {
            $xml = $zip->getFromName($xmlFile);
            if ($xml === false) continue;              // optional: skip if not present
            $dom = new \DOMDocument();
            if (!$dom->loadXML($xml, LIBXML_PARSEHUGE)) {
                libxml_clear_errors();
                $zip->close();
                return false;
            }
            libxml_clear_errors();
        }

        // Optional sanity pass on external links ONLY if rels exists
        $relsXml = $zip->getFromName('word/_rels/document.xml.rels');
        if ($relsXml !== false) {
            $dom = new \DOMDocument();
            if (@$dom->loadXML($relsXml, LIBXML_PARSEHUGE)) {
                foreach ($dom->getElementsByTagName('Relationship') as $rel) {
                    if (strtolower($rel->getAttribute('TargetMode')) === 'external') {
                        $target = $rel->getAttribute('Target');
                        if (strlen($target) > 1900 || preg_match('#[\x00-\x1F\x7F]#', $target)) {
                            $zip->close();
                            return false;
                        }
                    }
                }
            }
        }

        $zip->close();
        return true;
    }




    public function generatePdf(string $docxPath): ?string
    {
        try {
            $disk = Storage::disk('public');
            $docxFullPath = $disk->path($docxPath);
            if (!is_file($docxFullPath)) {
                return null;
            }

            if (!$this->configurePdfRenderer()) {
                return null;
            }

            $phpWord = IOFactory::load($docxFullPath);
            $pdfPath = preg_replace('/\.docx$/i', '.pdf', $docxPath) ?? ($docxPath . '.pdf');
            $pdfFullPath = $disk->path($pdfPath);

            $directory = dirname($pdfPath);
            if ($directory && $directory !== '.' && !$disk->exists($directory)) {
                $disk->makeDirectory($directory);
            }

            IOFactory::createWriter($phpWord, 'PDF')->save($pdfFullPath);

            clearstatcache(true, $pdfFullPath);

            if (!is_file($pdfFullPath) || filesize($pdfFullPath) === 0) {
                return null;
            }

            return $pdfPath;
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    private function initialiseDocument(): PhpWord
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(10.5);
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::EN_US));

        $phpWord->addNumberingStyle('Bullet', [
            'type' => 'multilevel',
            'levels' => [
                ['format' => 'bullet', 'text' => '•', 'left' => 360, 'hanging' => 200, 'tabPos' => 360],
                ['format' => 'bullet', 'text' => '◦', 'left' => 720, 'hanging' => 200, 'tabPos' => 720],
            ],
        ]);

        return $phpWord;
    }

    private function sanitizeCvData(array $data): array
    {
        $xmlSafe = function (?string $s): string {
            if ($s === null) return '';
            $s = mb_convert_encoding($s, 'UTF-8', 'UTF-8');
            // allow: 0x9,0xA,0xD,0x20-0xD7FF,0xE000-0xFFFD,0x10000-0x10FFFF
            return (string) preg_replace(
                '/[^\x09\x0A\x0D\x20-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u',
                '',
                $s
            );
        };

        $walk = function ($v) use (&$walk, $xmlSafe) {
            if (is_array($v)) return array_map($walk, $v);
            if (is_string($v)) return $xmlSafe($v);
            return $v;
        };

        return $walk($data);
    }


    // helper for percentage widths in tables that use 'unit' => 'pct'
    private function pct(float $percent): int
    {
        // Word expects fiftieths of a percent when unit='pct'
        return (int) round($percent * 50);
    }


    private function prepareForTemplate(array $cvData): array
    {
        return $this->sanitizeCvData($cvData);
    }


    private function resolvePhotoPath(?string $photoPath): ?string
    {
        if (!$photoPath) {
            return null;
        }

        if (Storage::disk('public')->exists($photoPath)) {
            return Storage::disk('public')->path($photoPath);
        }

        $prefixed = 'profile_photos/' . ltrim($photoPath, '/');
        if (Storage::disk('public')->exists($prefixed)) {
            return Storage::disk('public')->path($prefixed);
        }

        if (is_file($photoPath)) {
            return $photoPath;
        }

        $absolute = storage_path('app/public/' . ltrim($photoPath, '/'));
        if (is_file($absolute)) {
            return $absolute;
        }

        return null;
    }

    private function addProfilePhoto($element, array $cvData, array $options = []): bool
    {
        $path = $this->resolvePhotoPath(Arr::get($cvData, 'photo'));
        if (!$path) return false;

        // skip formats Word dislikes
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png'])) return false;

        $defaults = ['width' => 90, 'height' => 90, 'alignment' => Jc::RIGHT, 'marginTop' => 0];
        $style = array_merge($defaults, $options);

        $element->addImage($path, $style);
        return true;
    }


    private function renderMinimalTemplate(PhpWord $phpWord, array $cvData): void
    {
        $section = $this->addA4Section($phpWord);

        $colors = [
            'primary' => '1F2937',
            'accent' => '2563EB',
            'muted' => '6B7280',
            'border' => 'E5E7EB',
        ];

        $header = $section->addTable(['width' => 100 * 50, 'unit' => 'pct', 'cellMargin' => 80]);
        $header->addRow();
        $left = $header->addCell(7000, ['valign' => 'center']);
        $right = $header->addCell(3000, ['valign' => 'top']);

        $left->addText(Str::upper($cvData['name'] ?? 'Candidate'), ['size' => 26, 'bold' => true, 'color' => $colors['primary']], ['spaceAfter' => 80]);
        if (!empty($cvData['headline'])) {
            $left->addText($cvData['headline'], ['size' => 12, 'bold' => true, 'color' => $colors['accent']], ['spaceAfter' => 120]);
        }

        if ($contact = $this->formatContactLine($cvData)) {
            $left->addText($contact, ['color' => $colors['muted']], ['spaceAfter' => 80]);
        }

        collect($cvData['social_links'] ?? [])
            ->filter(fn ($link) => filled($link['url'] ?? null))
            ->each(function ($link) use ($left, $colors) {
                $label = trim(($link['label'] ?? '') . ':');
                $left->addText($label . ' ' . ($link['url'] ?? ''), ['color' => $colors['accent']], ['spaceAfter' => 40]);
            });

        if ($this->addProfilePhoto($right, $cvData, ['alignment' => Jc::RIGHT, 'width' => 90, 'height' => 90])) {
            $right->addTextBreak(1);
        }

        $this->renderAnalysisCard(Arr::get($cvData, 'analysis_snapshot'), $right, $colors['accent']);

        $section->addTextBreak(1);
        $section->addLine(['weight' => 1, 'width' => 9000, 'color' => $colors['border']]);
        $section->addTextBreak(1);

        if (!empty($cvData['about'])) {
            $this->addSectionHeading($section, 'Professional Summary', $colors['primary']);
            $section->addText($cvData['about'], ['color' => $colors['primary']], ['spaceAfter' => 160, 'lineHeight' => 1.2]);
        }

        $keywords = collect(Arr::get($cvData, 'meta.keywords', []))->filter()->take(8);
        if ($keywords->isNotEmpty()) {
            $section->addText('Keywords: ' . $keywords->implode(' · '), ['color' => $colors['accent'], 'italic' => true], ['spaceAfter' => 160]);
        }

        $achievements = $this->topAchievements($cvData);
        if ($achievements->isNotEmpty()) {
            $this->addSectionHeading($section, 'Key Achievements', $colors['primary']);
            $this->addBulletList($section, $achievements->all());
        }

        $this->addSectionHeading($section, 'Core Skills', $colors['primary']);
        $this->renderSkillsGrid($section, $cvData['skills'] ?? [], 3, $colors['accent'], $colors['muted']);

        $this->addSectionHeading($section, 'Professional Experience', $colors['primary']);
        $this->renderExperienceEntries($section, $cvData['experience'] ?? [], $colors['accent'], $colors['muted']);

        if (!empty($cvData['projects'])) {
            $this->addSectionHeading($section, 'Projects', $colors['primary']);
            $this->renderProjects($section, $cvData['projects'], $colors['accent'], $colors['muted']);
        }

        if (!empty($cvData['education'])) {
            $this->addSectionHeading($section, 'Education', $colors['primary']);
            $this->renderEducationEntries($section, $cvData['education'], $colors['accent'], $colors['muted']);
        }

        if (!empty($cvData['certifications'])) {
            $this->addSectionHeading($section, 'Certifications', $colors['primary']);
            $this->renderCertifications($section, $cvData['certifications'], $colors['accent'], $colors['muted']);
        }

        if (!empty($cvData['languages'])) {
            $this->addSectionHeading($section, 'Languages', $colors['primary']);
            $this->renderLanguages($section, $cvData['languages'], $colors['muted']);
        }

        if (!empty($cvData['interests'])) {
            $this->addSectionHeading($section, 'Interests', $colors['primary']);
            $this->renderInterests($section, $cvData['interests'], $colors['muted']);
        }

        if (!empty($cvData['references'])) {
            $this->addSectionHeading($section, 'References', $colors['primary']);
            $this->renderReferences($section, $cvData['references'], $colors['muted']);
        }

        $this->addGeneratedFooter($section, $cvData, $colors['muted']);
    }

    private function renderBusinessTemplate(PhpWord $phpWord, array $cvData): void
    {
        $section = $this->addA4Section($phpWord, [
            'marginLeft'  => 1020,
            'marginRight' => 1020,
        ]);

        $colors = [
            'headerBg'  => '0F172A',
            'headerText'=> 'FFFFFF',
            'accent'    => 'F97316',
            'muted'     => '64748B',
            'primary'   => '1E293B',
            'sidebarBg' => 'F8FAFC',
        ];

        // === Header (true 2-cell layout; no gridSpan) ===
        $headerTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct', 'cellMargin' => 120]);
        $headerTable->addRow();

        $hLeft  = $headerTable->addCell($this->pct(78), ['shading' => ['fill' => $colors['headerBg']], 'valign' => 'center']);
        $hRight = $headerTable->addCell($this->pct(22), ['shading' => ['fill' => $colors['headerBg']], 'valign' => 'center']);


        $hLeft->addText($cvData['name'] ?? 'Candidate', ['size' => 28, 'bold' => true, 'color' => $colors['headerText']], ['spaceAfter' => 40]);
        if (!empty($cvData['headline'])) {
            $hLeft->addText($cvData['headline'], ['color' => $colors['accent'], 'bold' => true, 'size' => 12], ['spaceAfter' => 40]);
        }
        if ($contact = $this->formatContactLine($cvData)) {
            $hLeft->addText($contact, ['color' => $colors['headerText']], ['spaceAfter' => 40]);
        }
        $analysis = Arr::get($cvData, 'analysis_snapshot');
        if (($score = Arr::get($analysis, 'score')) !== null) {
            $hLeft->addText('ATS Score: ' . $score . '/100', ['color' => $colors['headerText'], 'size' => 10], ['spaceAfter' => 0]);
        }

        // Photo on the right (no extra left/right margins that push outside)
        $this->addProfilePhoto($hRight, $cvData, ['alignment' => Jc::RIGHT, 'width' => 80, 'height' => 80, 'marginTop' => 12]);

        // === 2-column main layout ===
        $layout = $section->addTable(['width' => 100 * 50, 'unit' => 'pct', 'cellMargin' => 120]);
        $layout->addRow();
        $sidebar = $layout->addCell($this->pct(34), ['valign' => 'top', 'shading' => ['fill' => $colors['sidebarBg']], 'borderColor' => 'E2E8F0', 'borderSize' => 6]);
        $main    = $layout->addCell($this->pct(66), ['valign' => 'top']);


        $this->addSectionHeading($sidebar, 'Contact', $colors['primary'], ['spaceBefore' => 0, 'spaceAfter' => 60]);
        foreach ($this->formatContactItems($cvData) as $item) {
            $sidebar->addText($item, ['color' => $colors['muted']], ['spaceAfter' => 40]);
        }
        collect($cvData['social_links'] ?? [])
            ->filter(fn ($link) => filled($link['url'] ?? null))
            ->each(function ($link) use ($sidebar, $colors) {
                $sidebar->addText(($link['label'] ?? 'Link') . ': ' . ($link['url'] ?? ''), ['color' => $colors['accent']], ['spaceAfter' => 40]);
            });

        if (!empty($cvData['skills'])) {
            $this->addSectionHeading($sidebar, 'Core Skills', $colors['primary']);
            $this->renderSkillsGrid($sidebar, $cvData['skills'], 1, $colors['accent'], $colors['muted']);
        }

        if (!empty($cvData['certifications'])) {
            $this->addSectionHeading($sidebar, 'Certifications', $colors['primary']);
            $this->renderCertifications($sidebar, $cvData['certifications'], $colors['accent'], $colors['muted']);
        }

        if (!empty($cvData['languages'])) {
            $this->addSectionHeading($sidebar, 'Languages', $colors['primary']);
            $this->renderLanguages($sidebar, $cvData['languages'], $colors['muted']);
        }

        if (!empty($cvData['interests'])) {
            $this->addSectionHeading($sidebar, 'Interests', $colors['primary']);
            $this->renderInterests($sidebar, $cvData['interests'], $colors['muted']);
        }

        if (!empty($cvData['references'])) {
            $this->addSectionHeading($sidebar, 'References', $colors['primary']);
            $this->renderReferences($sidebar, $cvData['references'], $colors['muted']);
        }

        if (!empty($cvData['about'])) {
            $this->addSectionHeading($main, 'Executive Summary', $colors['primary'], ['spaceBefore' => 0]);
            $main->addText($cvData['about'], ['color' => $colors['primary']], ['spaceAfter' => 160, 'lineHeight' => 1.2]);
        }

        $achievements = $this->topAchievements($cvData);
        if ($achievements->isNotEmpty()) {
            $this->addSectionHeading($main, 'Highlights', $colors['primary']);
            $this->addBulletList($main, $achievements->all());
        }

        $this->addSectionHeading($main, 'Professional Experience', $colors['primary']);
        $this->renderExperienceEntries($main, $cvData['experience'] ?? [], $colors['accent'], $colors['muted']);

        if (!empty($cvData['projects'])) {
            $this->addSectionHeading($main, 'Selected Projects', $colors['primary']);
            $this->renderProjects($main, $cvData['projects'], $colors['accent'], $colors['muted']);
        }

        if (!empty($cvData['education'])) {
            $this->addSectionHeading($main, 'Education', $colors['primary']);
            $this->renderEducationEntries($main, $cvData['education'], $colors['accent'], $colors['muted']);
        }

        $this->addGeneratedFooter($section, $cvData, $colors['muted']);
    }

    private function addSafeLink($container, string $url, ?string $label = null, array $style = []): void
    {
        $label = $label ?? $url;

        // Normalise & validate the URL
        $u = trim($url);
        if ($u !== '' && !preg_match('#^(https?://|mailto:)#i', $u)) {
            $u = 'https://' . $u; // force scheme to avoid invalid Relationship
        }

        // Some Word builds choke on very long targets or illegal chars – fall back to plain text
        $isTooLong = strlen($u) > 1900; // safe ceiling for relationship targets
        $hasBadChar = preg_match('#[\x00-\x1F\x7F]#', $u); // control chars

        try {
            if ($u === '' || $isTooLong || $hasBadChar) {
                $container->addText(' ' . $label . ' ' . $url, $style);
            } else {
                $container->addLink($u, $label, $style);
            }
        } catch (\Throwable $e) {
            // absolutely never let link generation break the DOCX
            $container->addText(' ' . $label . ' ' . $url, $style);
        }
    }

    private function debugDocx(string $path): void
    {
        try {
            $zip = new \ZipArchive();
            if ($zip->open($path) !== true) return;

            $entries = ['word/document.xml', 'word/_rels/document.xml.rels', 'word/numbering.xml'];
            foreach ($entries as $e) {
                $xml = $zip->getFromName($e);
                if ($xml === false) continue;
                libxml_use_internal_errors(true);
                $dom = new \DOMDocument();
                if (!$dom->loadXML($xml)) {
                    $err = libxml_get_last_error();
                    Log::error("DOCX XML error in {$e}: " . ($err ? $err->message : 'unknown'));
                }
                libxml_clear_errors();
            }
            $zip->close();
        } catch (\Throwable $t) {
            Log::error('DOCX debug failed: ' . $t->getMessage());
        }
    }




    private function renderTechTemplate(PhpWord $phpWord, array $cvData): void
    {
        // SAFE: no tables, no images, no hyperlinks
        $section = $this->addA4Section($phpWord, [
            'marginTop'   => 720,
            'marginLeft'  => 1020,
            'marginRight' => 1020,
        ]);

        $colors = [
            'brand'   => '0B3D91', // header/brand color
            'accent'  => '38BDF8',
            'primary' => '0F172A',
            'muted'   => '475569',
        ];

        // --- Header (paragraphs only)
        $section->addText(
            $cvData['name'] ?? 'Candidate',
            ['size' => 28, 'bold' => true, 'color' => $colors['brand']],
            ['spaceAfter' => 40]
        );

        if (!empty($cvData['headline'])) {
            $section->addText(
                $cvData['headline'],
                ['size' => 12, 'bold' => true, 'color' => $colors['accent']],
                ['spaceAfter' => 40]
            );
        }

        if ($contact = $this->formatContactLine($cvData)) {
            $section->addText($contact, ['color' => $colors['muted']], ['spaceAfter' => 80]);
        }

        if (($score = Arr::get($cvData, 'analysis_snapshot.score')) !== null) {
            $section->addText('ATS Score: ' . $score . '/100', ['size' => 10, 'color' => $colors['muted']], ['spaceAfter' => 120]);
        }

        // --- Tech Stack
        if (!empty($cvData['skills'])) {
            $this->addSectionHeading($section, 'Tech Stack', $colors['primary'], ['spaceBefore' => 160, 'spaceAfter' => 60]);
            $this->renderSkillsInline($section, $cvData['skills'], $colors['accent']);
        }

        // --- Profile
        if (!empty($cvData['about'])) {
            $this->addSectionHeading($section, 'Profile', $colors['primary']);
            $section->addText($cvData['about'], ['color' => $colors['primary']], ['spaceAfter' => 160, 'lineHeight' => 1.25]);
        }

        // --- Impact Highlights
        $achievements = $this->topAchievements($cvData);
        if ($achievements->isNotEmpty()) {
            $this->addSectionHeading($section, 'Impact Highlights', $colors['primary']);
            $this->addBulletList($section, $achievements->all());
        }

        // --- Experience
        $this->addSectionHeading($section, 'Experience', $colors['primary']);
        $this->renderExperienceEntries($section, $cvData['experience'] ?? [], $colors['accent'], $colors['muted']);

        // --- Projects (plain text, no hyperlinks)
        if (!empty($cvData['projects'])) {
            $this->addSectionHeading($section, 'Recent Projects', $colors['primary']);
            $this->renderProjectsPlain($section, $cvData['projects'], $colors['accent'], $colors['muted']);
        }

        // --- Certifications
        if (!empty($cvData['certifications'])) {
            $this->addSectionHeading($section, 'Certifications & Awards', $colors['primary']);
            $this->renderCertifications($section, $cvData['certifications'], $colors['accent'], $colors['muted']);
        }

        // --- Education
        if (!empty($cvData['education'])) {
            $this->addSectionHeading($section, 'Education', $colors['primary']);
            $this->renderEducationEntries($section, $cvData['education'], $colors['accent'], $colors['muted']);
        }

        // --- Languages
        if (!empty($cvData['languages'])) {
            $this->addSectionHeading($section, 'Languages', $colors['primary']);
            $this->renderLanguages($section, $cvData['languages'], $colors['muted']);
        }

        $this->addGeneratedFooter($section, $cvData, $colors['muted']);
    }



    // Inline skills (no tables)
    private function renderSkillsInline($element, array $skills, string $accent): void
    {
        $names = collect($skills)
            ->map(fn ($s) => trim($s['name'] ?? ''))
            ->filter()
            ->unique()
            ->values();

        if ($names->isEmpty()) return;

        $element->addText($names->implode(' • '), ['color' => $accent], ['spaceAfter' => 120]);
    }

    // Projects printed as plain text (no addLink)
    private function renderProjectsPlain($element, array $projects, string $accent, string $muted): void
    {
        $items = collect($projects)->filter(fn ($p) => !empty($p['name']) || !empty($p['description']) || !empty($p['link']));
        if ($items->isEmpty()) return;

        $items->each(function ($p) use ($element, $accent, $muted) {
            $line = array_filter([
                $p['name'] ?? null,
                !empty($p['link']) ? '[' . $p['link'] . ']' : null, // printed, not hyperlinked
            ]);
            if (!empty($line)) {
                $element->addText(implode(' – ', $line), ['bold' => true, 'color' => $accent], ['spaceAfter' => 40]);
            }
            if (!empty($p['description'])) {
                $element->addText($p['description'], ['color' => $muted], ['spaceAfter' => 80, 'lineHeight' => 1.2]);
            }
        });
    }


    private function addSectionHeading($element, string $label, string $color, array $paragraph = []): void
    {
        $paragraph = array_merge(['spaceBefore' => 160, 'spaceAfter' => 80], $paragraph);
        $element->addText(Str::upper($label), ['bold' => true, 'color' => $color, 'size' => 11], $paragraph);
    }

    private function renderAnalysisCard(?array $analysis, $element, string $accent): void
    {
        if (empty($analysis)) {
            return;
        }

        $score = Arr::get($analysis, 'score');
        $highlights = collect(Arr::get($analysis, 'highlights', []))->filter();
        $warnings = collect(Arr::get($analysis, 'warnings', []))->filter();

        if ($score === null && $highlights->isEmpty() && $warnings->isEmpty()) {
            return;
        }

        if ($score !== null) {
            $element->addText('ATS Score: ' . $score . '/100', ['bold' => true, 'color' => $accent], ['spaceAfter' => 60]);
        }

        if ($highlights->isNotEmpty()) {
            $message = Arr::get($highlights->first(), 'message', $highlights->first());
            $element->addText('✓ ' . $message, ['size' => 9, 'color' => '047857'], ['spaceAfter' => 20]);
        }

        if ($warnings->isNotEmpty()) {
            $message = Arr::get($warnings->first(), 'message', $warnings->first());
            $element->addText('⚠ ' . $message, ['size' => 9, 'color' => 'B91C1C'], ['spaceAfter' => 20]);
        }
    }

    private function renderSkillsGrid($element, array $skills, int $columns, string $accent, string $muted): void
    {
        $names = collect($skills)
            ->map(fn ($skill) => Str::title($skill['name'] ?? ''))
            ->filter()
            ->unique()
            ->values();

        if ($names->isEmpty()) {
            return;
        }

        $columns = max(1, $columns);
        $rows = (int) ceil($names->count() / $columns);
        $table = $element->addTable(['unit' => 'pct', 'width' => 100 * 50, 'cellMargin' => 60]);

        for ($row = 0; $row < $rows; $row++) {
            $table->addRow();
            for ($col = 0; $col < $columns; $col++) {
                $index = $row + $rows * $col;
                $cell = $table->addCell((int) floor(5000 / $columns), ['valign' => 'center']);
                if ($name = $names->get($index)) {
                    $cell->addText($name, ['color' => $accent], ['spaceAfter' => 0]);
                }
            }
        }
    }

    private function renderSkillTags(Section $section, array $skills, string $accent, string $muted): void
    {
        $names = collect($skills)
            ->map(fn ($skill) => Str::upper($skill['name'] ?? ''))
            ->filter()
            ->unique()
            ->values();

        if ($names->isEmpty()) {
            return;
        }

        $perRow = 4;
        $table = $section->addTable(['unit' => 'pct', 'width' => 100 * 50, 'cellMargin' => 60]);
        $table->addRow();

        foreach ($names as $index => $name) {
            if ($index > 0 && $index % $perRow === 0) {
                $table->addRow();
            }
            $cell = $table->addCell((int) floor(5000 / $perRow), ['shading' => ['fill' => 'E0F2FE'], 'valign' => 'center']);
            $cell->addText($name, ['size' => 9, 'bold' => true, 'color' => $accent], ['alignment' => Jc::CENTER]);
        }
    }

    private function renderExperienceEntries($element, array $experience, string $accent, string $muted, bool $compact = false): void
    {
        $entries = collect($experience)->filter(fn ($role) => !empty($role));
        if ($entries->isEmpty()) {
            $element->addText('Experience details not provided.', ['color' => $muted], ['spaceAfter' => 120]);
            return;
        }

        $entries->each(function ($role) use ($element, $accent, $muted, $compact) {
            $heading = $this->formatExperienceHeading($role);
            $headingLine = trim(($heading['title'] ?? '') . ' — ' . ($heading['company'] ?? ''));
            if ($headingLine !== '') {
                $element->addText($headingLine, ['bold' => true, 'color' => $accent], ['spaceAfter' => $compact ? 40 : 60]);
            }

            $meta = [];
            if ($timeline = $this->formatTimeline($role)) {
                $meta[] = $timeline;
            }
            if (!empty($heading['location'])) {
                $meta[] = $heading['location'];
            }
            if (!empty($meta)) {
                $element->addText(implode(' · ', $meta), ['color' => $muted, 'italic' => true], ['spaceAfter' => $compact ? 40 : 80]);
            }

            $achievements = collect($role['achievements'] ?? [])->filter();
            if ($achievements->isNotEmpty()) {
                $this->addBulletList($element, $achievements->all());
            } elseif (!empty($role['description'])) {
                $element->addText($role['description'], ['color' => $muted], ['spaceAfter' => 80, 'lineHeight' => 1.2]);
            }

            $element->addTextBreak($compact ? 0 : 1);
        });
    }

    private function renderEducationEntries($element, array $education, string $accent, string $muted): void
    {
        $entries = collect($education)->filter(fn ($entry) => !empty($entry['degree']) || !empty($entry['institution']));
        if ($entries->isEmpty()) {
            return;
        }

        $entries->each(function ($entry) use ($element, $accent, $muted) {
            $line = trim(($entry['degree'] ?? '') . ' — ' . ($entry['institution'] ?? ''));
            if ($line !== '') {
                $element->addText($line, ['bold' => true, 'color' => $accent], ['spaceAfter' => 40]);
            }

            $details = [];
            if (!empty($entry['completion_year'])) {
                $details[] = (string) $entry['completion_year'];
            }
            if (!empty($entry['notes'])) {
                $details[] = $entry['notes'];
            }

            if (!empty($details)) {
                $element->addText(implode(' · ', $details), ['color' => $muted], ['spaceAfter' => 80]);
            }
        });
    }

    private const A4_W = 11906;   // twips
    private const A4_H = 16838;   // twips

    private function addA4Section(PhpWord $phpWord, array $opts = []): Section
    {
        $defaults = [
            'orientation' => 'portrait',
            'pageSizeW'   => self::A4_W,
            'pageSizeH'   => self::A4_H,
            // give Business a touch more inner space to avoid visual clipping
            'marginTop'   => 720,
            'marginBottom'=> 720,
            'marginLeft'  => 900,
            'marginRight' => 900,
            'headerHeight'=> 720,
            'footerHeight'=> 720,
        ];
        return $phpWord->addSection(array_replace($defaults, $opts));
    }


    private function renderProjects($element, array $projects, string $accent, string $muted): void
    {
        $items = collect($projects)->filter(fn ($project) => !empty($project['name']) || !empty($project['description']));
        if ($items->isEmpty()) {
            return;
        }

        $items->each(function ($project) use ($element, $accent, $muted) {
            $run = $element->addTextRun(['spaceAfter' => 40]);
            if (!empty($project['name'])) {
                $run->addText($project['name'], ['bold' => true, 'color' => $accent]);
            }
            if (!empty($project['link'])) {
                $run->addText(' – ');
                $this->addSafeLink($run, (string) $project['link'], (string) $project['link'], ['color' => $accent]);
            }


            if (!empty($project['description'])) {
                $element->addText($project['description'], ['color' => $muted], ['spaceAfter' => 80, 'lineHeight' => 1.2]);
            }
        });
    }

    private function renderCertifications($element, array $certifications, string $accent, string $muted): void
    {
        $items = collect($certifications)->filter(fn ($cert) => !empty($cert['name']));
        if ($items->isEmpty()) {
            return;
        }

        $items->each(function ($cert) use ($element, $accent, $muted) {
            $parts = array_filter([
                $cert['name'] ?? null,
                $cert['issuer'] ?? null,
                isset($cert['year']) ? (string) $cert['year'] : null,
            ]);
            $element->addText(implode(' · ', $parts), ['color' => $accent], ['spaceAfter' => 60]);
            if (!empty($cert['notes'])) {
                $element->addText($cert['notes'], ['color' => $muted], ['spaceAfter' => 60]);
            }
        });
    }

    private function renderLanguages($element, array $languages, string $muted): void
    {
        $items = collect($languages)->filter(fn ($language) => !empty($language['name']));
        if ($items->isEmpty()) {
            return;
        }

        $items->each(function ($language) use ($element, $muted) {
            $line = trim(($language['name'] ?? '') . ' — ' . ($language['level'] ?? ''));
            if ($line !== '') {
                $element->addText($line, ['color' => $muted], ['spaceAfter' => 60]);
            }
        });
    }

    private function renderInterests($element, array $interests, string $muted): void
    {
        $items = collect($interests)
            ->map(fn ($interest) => is_array($interest) ? ($interest['name'] ?? '') : $interest)
            ->filter();

        if ($items->isEmpty()) {
            return;
        }

        $element->addText($items->implode(', '), ['color' => $muted], ['spaceAfter' => 80]);
    }

    private function renderReferences($element, array $references, string $muted): void
    {
        $items = collect($references)->filter(fn ($ref) => !empty($ref['name']) || !empty($ref['contact']));
        if ($items->isEmpty()) {
            return;
        }

        $items->each(function ($ref) use ($element, $muted) {
            $line = trim(($ref['name'] ?? '') . ' — ' . ($ref['contact'] ?? ''));
            if ($line !== '') {
                $element->addText($line, ['color' => $muted], ['spaceAfter' => 60]);
            }
        });
    }

    private function addBulletList($element, array $items, int $level = 0): void
    {
        foreach ($items as $item) {
            if (!filled($item)) {
                continue;
            }
            $element->addListItem($item, $level, [], 'Bullet');
        }
    }

    private function addGeneratedFooter(Section $section, array $cvData, string $muted): void
    {
        $section->addTextBreak(1);
        $section->addText(
            'Generated: ' . Carbon::parse($cvData['meta']['generated_at'] ?? now())->format('Y-m-d H:i'),
            ['color' => $muted, 'italic' => true],
            ['alignment' => Jc::RIGHT]
        );
    }

    private function formatContactLine(array $cvData): ?string
    {
        $items = array_filter([
            $cvData['email'] ?? null,
            $cvData['phone'] ?? null,
            $cvData['location'] ?? null,
        ]);

        return empty($items) ? null : implode(' · ', $items);
    }

    private function formatContactItems(array $cvData): array
    {
        $items = [];
        if (!empty($cvData['email'])) {
            $items[] = $cvData['email'];
        }
        if (!empty($cvData['phone'])) {
            $items[] = $cvData['phone'];
        }
        if (!empty($cvData['location'])) {
            $items[] = $cvData['location'];
        }

        return $items;
    }

    private function topAchievements(array $cvData)
    {
        $experience = collect($cvData['experience'] ?? []);

        return $experience
            ->flatMap(fn ($role) => collect($role['achievements'] ?? [])->map(fn ($item) => ['text' => $item, 'role' => $role]))
            ->filter(fn ($item) => !empty($item['text']))
            ->sortByDesc(function ($item) {
                return $this->scoreAchievement($item['text']);
            })
            ->pluck('text')
            ->take(4);
    }

    private function scoreAchievement(string $text): int
    {
        $score = 0;
        if (preg_match('/\d+/', $text)) {
            $score += 3;
        }
        if (Str::contains(Str::lower($text), ['%', '€', '$', 'k'])) {
            $score += 2;
        }
        if ($this->containsActionVerb($text)) {
            $score += 1;
        }

        return $score;
    }

    private function containsActionVerb(string $text): bool
    {
        $verbs = ['increased', 'improved', 'reduced', 'launched', 'delivered', 'built', 'created', 'initiated', 'led', 'optimised', 'optimized'];
        $lower = Str::lower($text);

        foreach ($verbs as $verb) {
            if (Str::contains($lower, $verb)) {
                return true;
            }
        }

        return false;
    }

    private function formatExperienceHeading(array $role): array
    {
        return [
            'title' => $role['title'] ?? '',
            'company' => $role['company'] ?? '',
            'location' => $role['location'] ?? '',
        ];
    }

    private function formatTimeline(array $role): ?string
    {
        $start = $this->parseDate($role['start_date'] ?? null);
        $end = $this->parseDate($role['end_date'] ?? null);

        if (!$start && !$end) {
            return null;
        }

        $pattern = 'M Y';
        $startLabel = $start ? $start->format($pattern) : '—';
        $endLabel = $end ? $end->format($pattern) : 'present';

        $tenureMonths = $role['tenure_months'] ?? ($start ? ($end?->diffInMonths($start) ?? Carbon::now()->diffInMonths($start)) : null);
        $tenure = $this->formatTenure((int) $tenureMonths);

        return trim($startLabel . ' – ' . $endLabel . ($tenure ? ' · ' . $tenure : ''));
    }

    private function formatTenure(?int $months): ?string
    {
        if (!$months || $months <= 0) {
            return null;
        }

        $years = intdiv($months, 12);
        $remaining = $months % 12;

        $parts = [];
        if ($years > 0) {
            $parts[] = $years . ' yr';
        }
        if ($remaining > 0) {
            $parts[] = $remaining . ' mo';
        }

        return implode(' ', $parts);
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

    private function configurePdfRenderer(): bool
    {
        if (class_exists(\Mpdf\Mpdf::class)) {
            $mpdfPath = base_path('vendor/mpdf/mpdf');
            if (is_dir($mpdfPath)) {
                Settings::setPdfRendererName('MPDF');
                Settings::setPdfRendererPath($mpdfPath);

                return true;
            }
        }

        if (class_exists(\Dompdf\Dompdf::class)) {
            $domPdfPath = base_path('vendor/dompdf/dompdf');
            if (is_dir($domPdfPath)) {
                Settings::setPdfRendererName('DomPDF');
                Settings::setPdfRendererPath($domPdfPath);

                return true;
            }
        }

        Log::warning('Unable to configure PDF renderer for PhpWord. Install mpdf/mpdf or dompdf/dompdf to enable PDF exports.');

        return false;
    }
}
