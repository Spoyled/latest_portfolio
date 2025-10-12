@include('layouts.header')

@php
    $featuredCollection = $featuredPosts instanceof \Illuminate\Support\Collection
        ? $featuredPosts
        : collect($featuredPosts ?? []);
    $latestCollection = $latestPosts instanceof \Illuminate\Support\Collection
        ? $latestPosts
        : collect($latestPosts ?? []);
    $activeCount = $latestCollection->count();
    $featuredCount = $featuredCollection->count();
@endphp

<section class="relative isolate overflow-hidden bg-slate-950">
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}"
         alt="Team collaborating"
         class="absolute inset-0 h-full w-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/80 to-slate-900/65"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-20 sm:py-24 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Employer command center
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                Build teams faster with a streamlined hiring workspace.
            </h1>
            <p class="mt-3 text-lg text-slate-200 sm:text-xl">
                Post roles with clarity, monitor performance at a glance, and keep candidates moving through your pipeline.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('employer.make_post') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-yellow-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 4v16m8-8H4" />
                    </svg>
                    Post a new job
                </a>
                <a href="{{ route('employer.portfolios') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-white/25 px-6 py-3 text-sm font-semibold text-white transition hover:border-yellow-300 hover:text-yellow-200">
                    Manage active roles
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Active openings</p>
                <p class="mt-3 text-3xl.font-semibold text-white">{{ $activeCount }}</p>
                <p class="mt-2 text-sm text-slate-200">Stay visible on ProSnap and attract fresh candidates every day.</p>
            </div>
            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Featured roles</p>
                <p class="mt-3 text-3xl.font-semibold text-white">{{ $featuredCount }}</p>
                <p class="mt-2 text-sm text-slate-200">Showcase high-priority positions to keep them at the top of feeds.</p>
            </div>
            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Time to publish</p>
                <p class="mt-3 text-3xl font-semibold text-white">Minutes</p>
                <p class="mt-2 text-sm text-slate-200">Craft a polished posting with collaboration, metrics, and culture signals.</p>
            </div>
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl space-y-16 px-6 pb-20 pt-12 sm:px-8">

        <section class="grid gap-6 lg:grid-cols-[1fr,0.8fr]">
            <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-2xl font-semibold text-slate-900">Pipeline snapshot</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Keep your hiring team aligned with quick visibility into what's live, what’s featured, and what needs attention.
                </p>
                <ul class="mt-6 space-y-4 text-sm text-slate-600">
                    <li class="flex gap-3">
                        <span class="inline-flex h-2 w-2 rounded-full bg-blue-600"></span>
                        Review applicants and update status in one click from the role pages.
                    </li>
                    <li class="flex gap-3">
                        <span class="inline-flex h-2 w-2 rounded-full bg-blue-600"></span>
                        Highlight responsibilities, collaboration style, and success metrics so candidates self-select in.
                    </li>
                    <li class="flex gap-3">
                        <span class="inline-flex h-2 w-2 rounded-full bg-blue-600"></span>
                        Rotate featured roles each week to keep visibility high for hard-to-fill positions.
                    </li>
                </ul>
            </div>

            <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-2xl font-semibold text-slate-900">Quick actions</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <a href="{{ route('employer.make_post') }}"
                       class="group flex h-full flex-col justify-between rounded-2xl border border-slate-200 bg-slate-50 p-5 transition hover:-translate-y-1 hover:border-blue-500 hover:shadow-lg">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 group-hover:text-blue-600">Publish a job post</h3>
                            <p class="mt-2 text-sm text-slate-500">Structured prompts help you capture mission, impact, and culture effortlessly.</p>
                        </div>
                        <span class="mt-6 inline-flex items-center gap-2 text-xs font-semibold text-blue-600 group-hover:text-blue-700">
                            Create role
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </a>
                    <a href="{{ route('employer.portfolios') }}"
                       class="group flex h-full flex-col justify-between rounded-2xl border border-slate-200 bg-slate-50 p-5 transition hover:-translate-y-1 hover:border-blue-500 hover:shadow-lg">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 group-hover:text-blue-600">Review active openings</h3>
                            <p class="mt-2 text-sm text-slate-500">Check applicants, update status, and keep the pipeline organised.</p>
                        </div>
                        <span class="mt-6 inline-flex items-center gap-2 text-xs font-semibold text-blue-600 group-hover:text-blue-700">
                            Go to roles
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </section>

        <section class="space-y-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Featured openings</h2>
                    <p class="mt-1 text-sm text-slate-500">Signal key hires to candidates browsing the ProSnap network.</p>
                </div>
                <a href="{{ route('employer.all_posts') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                    View all postings
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($featuredCollection as $post)
                    @php
                        $route = route('employer.posts.show', $post->id);
                        $when = $post->published_at ?? $post->created_at ?? null;
                        $whenStr = '';
                        if ($when instanceof \Carbon\Carbon) {
                            $whenStr = $when->diffForHumans();
                        } elseif (is_string($when)) {
                            $whenStr = \Carbon\Carbon::parse($when)->diffForHumans();
                        }
                        $location = $post->location ?? 'Anywhere';
                        $salary = $post->salary ?? null;
                    @endphp

                    <a href="{{ $route }}"
                       class="group flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-48 w-full overflow-hidden">
                            <img src="{{ !empty($post->image) ? asset('storage/posts/' . $post->image) : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png' }}"
                                 alt="{{ $post->title }}"
                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            <span class="absolute left-4 top-4 inline-flex items-center rounded-full bg-yellow-400 px-3 py-1 text-xs font-semibold text-slate-900">Featured</span>
                        </div>
                        <div class="flex flex-1 flex-col gap-4 p-6">
                            <div>
                                <p class="text-xs text-slate-400">{{ $whenStr }}</p>
                                <h3 class="mt-1 text-lg font-semibold text-slate-900 transition group-hover:text-blue-600">{{ $post->title }}</h3>
                            </div>
                            <p class="text-sm font-medium text-slate-500">{{ $location }}</p>
                            <p class="text-sm text-slate-600">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->body ?? ''), 110) }}
                            </p>
                            <div class="mt-auto flex items-center justify-between text-xs font-semibold text-slate-500">
                                @if (!empty($salary))
                                    <span>From €{{ number_format((float) $salary, 0, '.', ' ') }}</span>
                                @endif
                                <span class="inline-flex items-center gap-1 text-blue-600 group-hover:text-blue-700">
                                    Open posting
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
                        <h3 class="text-lg font-semibold text-slate-900">No featured openings yet</h3>
                        <p class="mt-2 text-sm text-slate-500">Highlight a role to keep it front and center for candidates.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="space-y-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Recently posted</h2>
                    <p class="mt-1 text-sm text-slate-500">Keep momentum by sharing updates and refreshing high-demand roles.</p>
                </div>
                <a href="{{ route('employer.portfolios') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                    Manage listings
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($latestCollection as $post)
                    @php
                        $route = route('employer.posts.show', $post->id);
                        $when = $post->published_at ?? $post->created_at ?? null;
                        $whenStr = '';
                        if ($when instanceof \Carbon\Carbon) {
                            $whenStr = $when->diffForHumans();
                        } elseif (is_string($when)) {
                            $whenStr = \Carbon\Carbon::parse($when)->diffForHumans();
                        }
                        $location = $post->location ?? 'Anywhere';
                        $salary = $post->salary ?? null;
                    @endphp
                    <a href="{{ $route }}"
                       class="group flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-48 w-full overflow-hidden">
                            <img src="{{ !empty($post->image) ? asset('storage/posts/' . $post->image) : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png' }}"
                                 alt="{{ $post->title }}"
                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            <span class="absolute left-4 top-4 inline-flex items-center rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">Live</span>
                        </div>
                        <div class="flex flex-1 flex-col gap-4 p-6">
                            <div>
                                <p class="text-xs text-slate-400">{{ $whenStr }}</p>
                                <h3 class="mt-1 text-lg font-semibold text-slate-900 transition group-hover:text-blue-600">{{ $post->title }}</h3>
                            </div>
                            <p class="text-sm font-medium text-slate-500">{{ $location }}</p>
                            <p class="text-sm text-slate-600">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->body ?? ''), 120) }}
                            </p>
                            <div class="mt-auto flex items-center justify-between text-xs font-semibold text-slate-500">
                                @if (!empty($salary))
                                    <span>From €{{ number_format((float) $salary, 0, '.', ' ') }}</span>
                                @endif
                                <span class="inline-flex items-center gap-1 text-blue-600 group-hover:text-blue-700">
                                    Review posting
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
                        <h3 class="text-lg font-semibold text-slate-900">No active job posts yet</h3>
                        <p class="mt-2 text-sm text-slate-500">Share a role to start receiving qualified candidates.</p>
                        <a href="{{ route('employer.make_post') }}"
                           class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Publish a job
                        </a>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-2xl font-semibold text-slate-900">Hiring toolkit</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Collaborate quickly</h3>
                    <p class="mt-3 text-sm text-slate-600">
                        Share candidate notes with teammates to keep decisions moving even across time zones.
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Showcase culture</h3>
                    <p class="mt-3 text-sm text-slate-600">
                        Include mission, collaboration style, and success metrics in each post so candidates self-select in.
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Stay visible</h3>
                    <p class="mt-3 text-sm text-slate-600">
                        Refresh openings weekly and leverage the featured slot to keep priority roles top of feed.
                    </p>
                </div>
            </div>
        </section>
    </div>
</main>

@include('layouts.footer')
