<?php

namespace App\Support;

class ResumeBlueprint
{
    /**
     * Canonical section headings for structured resume bodies.
     */
    public const SECTION_TITLES = [
        'summary'      => 'Professional Snapshot',
        'highlights'   => 'Recent Wins',
        'ideal_role'   => 'Ideal Next Role',
        'collaboration'=> 'Collaboration Style',
        'availability' => 'Availability & Logistics',
    ];

    /**
     * Build a markdown-style body string from provided sections.
     *
     * @param  array<string,string|null>  $sections
     */
    public static function build(array $sections): string
    {
        return collect(self::SECTION_TITLES)
            ->map(function (string $heading, string $key) use ($sections) {
                $content = trim((string) ($sections[$key] ?? ''));
                if ($content === '') {
                    return null;
                }

                return "### {$heading}\n{$content}";
            })
            ->filter()
            ->implode("\n\n");
    }

    /**
     * Parse an existing body into section content.
     *
     * @return array<string,string|null>
     */
    public static function parse(?string $body): array
    {
        $result = collect(self::SECTION_TITLES)
            ->mapWithKeys(fn ($heading, $key) => [$key => null])
            ->all();

        if (!is_string($body) || trim($body) === '') {
            $result['summary'] = trim((string) $body) ?: null;
            return $result;
        }

        $pattern = '/^###\s*(.+?)\s*\R([\s\S]*?)(?=^###\s+|\z)/m';
        if (preg_match_all($pattern, $body, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                [, $heading, $content] = $match;
                $heading = trim($heading);
                $content = trim($content);

                $key = array_search($heading, self::SECTION_TITLES, true);
                if ($key !== false) {
                    $result[$key] = $content;
                }
            }
        } else {
            // Legacy posts without structured sections.
            $result['summary'] = trim($body);
        }

        return $result;
    }
}

