<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostsApi;
use Carbon\Carbon;

class ShowAllPosts extends Controller
{
    private PostsApi $api;

    public function __construct(PostsApi $api)
    {
        $this->api = $api;
    }

    public function index(Request $request)
    {
        $isEmployer     = auth('employer')->check();
        $wantedType     = $isEmployer ? 'resume' : 'job_offer';

        $salaryMin      = trim((string) $request->query('salary_min', ''));
        $nameFilter     = trim((string) $request->query('name_filter', ''));
        $locationFilter = trim((string) $request->query('location_filter', ''));
        $positionFilter = trim((string) $request->query('position_filter', ''));

        $rows = $this->api->list();

        // base scope: active, not closed, correct type
        $rows = array_filter($rows, function ($p) use ($wantedType) {
            $active    = (int)($p['is_active'] ?? 1) === 1;
            $notClosed = empty($p['closed_at']);
            return $active && $notClosed && (($p['post_type'] ?? '') === $wantedType);
        });

        // helpers for LT-friendly case-insensitive contains
        $norm = fn ($v) => mb_strtolower(trim((string)$v), 'UTF-8');
        $has  = fn ($needle, $haystack) => $needle === '' || strpos($norm($haystack), $norm($needle)) !== false;

        // apply UI filters
        $rows = array_values(array_filter($rows, function ($p) use ($isEmployer, $salaryMin, $nameFilter, $locationFilter, $positionFilter, $has) {
            if (!$isEmployer && $salaryMin !== '') {
                $sal = is_null($p['salary'] ?? null) ? 0 : (float)$p['salary'];
                if ($sal < (float)$salaryMin) return false;
            }

            // "Name" textbox should match title OR poster name OR position OR skills
            $title    = $p['title']    ?? '';
            $poster   = $p['name']     ?? '';
            $position = $p['position'] ?? '';
            $skills   = $p['skills']   ?? '';

            if ($nameFilter !== '') {
                $hay = $title.' '.$poster.' '.$position.' '.$skills;
                if (!$has($nameFilter, $hay)) return false;
            }

            if ($locationFilter !== '' && !$has($locationFilter, $p['location'] ?? '')) return false;
            if ($positionFilter !== '' && !$has($positionFilter, $position))       return false;

            return true;
        }));

        // newest first
        usort($rows, fn ($a, $b) =>
            strtotime($b['published_at'] ?? '1970-01-01') <=> strtotime($a['published_at'] ?? '1970-01-01')
        );

        $allPosts = collect(array_map(fn ($p) => (object)$p, $rows));

        return view('all_posts', compact('allPosts','salaryMin','nameFilter','locationFilter','positionFilter'));
    }
}
