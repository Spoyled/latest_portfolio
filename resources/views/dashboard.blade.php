<!-- Header -->
@include('layouts.header')

@php
    $user = auth()->user();
    $heroName = $user && isset($user->name) && $user->name !== '' ? $user->name : 'Professional';
    $featuredCount = $featuredPosts->count();
    $latestCount = $latestPosts->count();
    $hasProfilePhoto = $user && !empty($user->profile_photo_path);
@endphp

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}"
         alt="Collaborative workspace"
         class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/75 to-slate-900/60"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-20 sm:py-24 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Welcome back, {{ $heroName }}
            </p>
            <h1 class="mt-6 text-4xl font-semibold text-white sm:text-5xl">
                Let’s shape your next career move.
            </h1>
            <p class="mt-4 text-lg text-slate-200 sm:text-xl">
                Review curated opportunities, keep your profile current, and stay ahead of hiring teams actively looking on ProSnap.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('custom.profile.show') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-yellow-400 px-6 py-3 text-base font-semibold text-slate-900 transition hover:bg-yellow-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-200">
                    Update profile & CV
                </a>
                <a href="{{ route('all_posts.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-white/30 px-6 py-3 text-base font-semibold text-white transition hover:border-yellow-300 hover:text-yellow-200">
                    Browse all opportunities
                </a>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Featured roles</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $featuredCount }}</p>
                <p class="mt-2 text-sm text-slate-200">Hand-picked positions receiving the most engagement right now.</p>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">New this week</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $latestCount }}</p>
                <p class="mt-2 text-sm text-slate-200">Fresh opportunities published in the last few days.</p>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Profile status</p>
                <p class="mt-3 text-2xl font-semibold text-white">{{ $hasProfilePhoto ? 'On track' : 'Complete today' }}</p>
                <p class="mt-2 text-sm text-slate-200">
                    {{ $hasProfilePhoto
                        ? 'Your profile photo helps hiring teams recognise you immediately.'
                        : 'Add a profile photo and polish your summary to stand out.' }}
                </p>
            </div>
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl space-y-16 px-6 pb-20 pt-12 sm:px-8">

        <section class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('custom.profile.show') }}"
               class="group flex h-full flex-col justify-between rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <span class="inline-flex rounded-2xl bg-blue-100 p-3 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A4 4 0 018.235 16h7.53a4 4 0 013.114 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Profile & CV</p>
                        <h3 class="mt-2 text-lg font-semibold text-slate-900">Refresh your professional story</h3>
                        <p class="mt-2 text-sm text-slate-500">Fine-tune your experience, upload your latest CV, and stay recruiter-ready.</p>
                    </div>
                </div>
                <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 group-hover:text-blue-700">
                    Go to profile
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </a>

            <a href="{{ route('make_post.index') }}"
               class="group flex h-full flex-col justify-between rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <span class="inline-flex rounded-2xl bg-emerald-100 p-3 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-emerald-600">Showcase</p>
                        <h3 class="mt-2 text-lg font-semibold text-slate-900">Publish a spotlight resume</h3>
                        <p class="mt-2 text-sm text-slate-500">Share a focused highlight of your achievements and make it easy to discover you.</p>
                    </div>
                </div>
                <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 group-hover:text-emerald-700">
                    Create a new post
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </a>

            <a href="{{ route('portfolios.index') }}"
               class="group flex h-full flex-col justify-between rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <span class="inline-flex rounded-2xl bg-purple-100 p-3 text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-6a2 2 0 00-2-2H5m4 8h6m-6 0v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2m-6 0h.01"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-purple-600">Activity</p>
                        <h3 class="mt-2 text-lg font-semibold text-slate-900">Track your submissions</h3>
                        <p class="mt-2 text-sm text-slate-500">See everything you have shared with employers and keep it up to date.</p>
                    </div>
                </div>
                <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-purple-600 group-hover:text-purple-700">
                    Review my posts
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </a>

            <a href="#resources"
               class="group flex h-full flex-col justify-between rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <span class="inline-flex rounded-2xl bg-amber-100 p-3 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 11-4 0m4 0a2 2 0 004 0m0 2v2m0 12h-4m4 0h4m-8 0h-4m4-4v4m0-4a2 2 0 114 0m-4 0a2 2 0 11-4 0m4 0v-4"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-amber-600">Growth</p>
                        <h3 class="mt-2 text-lg font-semibold text-slate-900">Career resources</h3>
                        <p class="mt-2 text-sm text-slate-500">Discover curated tips, templates, and interview guidance tailored for you.</p>
                    </div>
                </div>
                <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-amber-600 group-hover:text-amber-700">
                    See recommendations
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </a>
        </section>

        <section>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-3xl font-semibold text-slate-900">Featured opportunities</h2>
                    <p class="mt-2 text-sm text-slate-500">Roles employers are actively promoting this week.</p>
                </div>
                <a href="{{ route('all_posts.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                    View all roles
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="mt-8 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($featuredPosts as $post)
                    @php
                        $route = route('posts.show', $post->id);
                        $when = $post->published_at ?? $post->created_at ?? null;
                        $whenStr = '';
                        if ($when instanceof \Carbon\Carbon) {
                            $whenStr = $when->format('M d, Y');
                        } elseif (is_string($when)) {
                            $whenStr = \Carbon\Carbon::parse($when)->format('M d, Y');
                        }
                        $imageUrl = !empty($post->image)
                            ? asset('storage/posts/' . $post->image)
                            : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png';
                        $summarySource = $post->summary
                            ?? $post->short_description
                            ?? $post->description
                            ?? null;
                        $summary = is_string($summarySource)
                            ? \Illuminate\Support\Str::limit(strip_tags($summarySource), 120)
                            : 'Preview the full opportunity to explore responsibilities, culture, and benefits.';
                        $company = $post->company_name ?? $post->company ?? null;
                        $location = $post->location ?? null;
                    @endphp

                    <a href="{{ $route }}"
                       class="group flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-48 w-full overflow-hidden">
                            <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            <span class="absolute left-4 top-4 inline-flex items-center rounded-full bg-yellow-400 px-3 py-1 text-xs font-semibold text-slate-900">Featured</span>
                        </div>
                        <div class="flex flex-1 flex-col gap-4 p-6">
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <span>{{ $whenStr }}</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 transition group-hover:text-blue-600">{{ $post->title }}</h3>
                                @if ($company || $location)
                                    <p class="mt-1 text-sm font-medium text-slate-500">
                                        @if ($company)
                                            <span>{{ $company }}</span>
                                        @endif
                                        @if ($company && $location)
                                            <span class="mx-2 text-slate-400">•</span>
                                        @endif
                                        @if ($location)
                                            <span>{{ $location }}</span>
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <p class="text-sm text-slate-600">{{ $summary }}</p>
                            <span class="mt-auto inline-flex items-center gap-2 text-sm font-semibold text-blue-600">
                                View details
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
                        <h3 class="text-lg font-semibold text-slate-900">Featured opportunities will appear here soon</h3>
                        <p class="mt-2 text-sm text-slate-500">Keep your profile current so employers can showcase your resume when a great match arrives.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-3xl font-semibold text-slate-900">Latest openings</h2>
                    <p class="mt-2 text-sm text-slate-500">Recently added roles that align with your profile and interests.</p>
                </div>
                <a href="{{ route('all_posts.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                    Discover more
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="mt-8 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($latestPosts as $post)
                    @php
                        $route = route('posts.show', $post->id);
                        $when = $post->published_at ?? $post->created_at ?? null;
                        $whenStr = '';
                        if ($when instanceof \Carbon\Carbon) {
                            $whenStr = $when->diffForHumans();
                        } elseif (is_string($when)) {
                            $whenStr = \Carbon\Carbon::parse($when)->diffForHumans();
                        }
                        $imageUrl = !empty($post->image)
                            ? asset('storage/posts/' . $post->image)
                            : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png';
                        $summarySource = $post->summary
                            ?? $post->short_description
                            ?? $post->description
                            ?? null;
                        $summary = is_string($summarySource)
                            ? \Illuminate\Support\Str::limit(strip_tags($summarySource), 120)
                            : 'Take a closer look to learn about responsibilities, required skills, and growth path.';
                        $company = $post->company_name ?? $post->company ?? null;
                        $location = $post->location ?? null;
                    @endphp

                    <a href="{{ $route }}"
                       class="group flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-48 w-full overflow-hidden">
                            <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            <span class="absolute left-4 top-4 inline-flex items-center rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">New</span>
                        </div>
                        <div class="flex flex-1 flex-col gap-4 p-6">
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <span>{{ $whenStr }}</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 transition group-hover:text-blue-600">{{ $post->title }}</h3>
                                @if ($company || $location)
                                    <p class="mt-1 text-sm font-medium text-slate-500">
                                        @if ($company)
                                            <span>{{ $company }}</span>
                                        @endif
                                        @if ($company && $location)
                                            <span class="mx-2 text-slate-400">•</span>
                                        @endif
                                        @if ($location)
                                            <span>{{ $location }}</span>
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <p class="text-sm text-slate-600">{{ $summary }}</p>
                            <span class="mt-auto inline-flex items-center gap-2 text-sm font-semibold text-blue-600">
                                View details
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
                        <h3 class="text-lg font-semibold text-slate-900">Stay tuned for fresh opportunities</h3>
                        <p class="mt-2 text-sm text-slate-500">We will let you know as soon as new positions match your goals.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section id="resources" class="rounded-3xl bg-white p-10 shadow-sm ring-1 ring-slate-200">
            <div class="mb-8 max-w-2xl">
                <h2 class="text-3xl font-semibold text-slate-900">Resources & next steps</h2>
                <p class="mt-2 text-sm text-slate-500">Keep the momentum going with these quick wins tailored for modern job seekers.</p>
            </div>
            <div class="grid gap-8 md:grid-cols-2">
                <div class="flex items-start gap-4">
                    <span class="mt-1 inline-flex rounded-2xl bg-sky-100 p-3 text-sky-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.866-3.582 7-8 7a9 9 0 1016 0c-4.418 0-8-3.134-8-7z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11V7a4 4 0 018 0"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Refresh your summary</h3>
                        <p class="mt-2 text-sm text-slate-500">Highlight measurable outcomes from your latest role—recruiters scan for quantifiable impact first.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <span class="mt-1 inline-flex rounded-2xl bg-rose-100 p-3 text-rose-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14v7m-7-3.5a3.5 3.5 0 017 0m7 0a3.5 3.5 0 00-7 0"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Prepare for conversations</h3>
                        <p class="mt-2 text-sm text-slate-500">Outline three concise stories that demonstrate leadership, problem-solving, and collaboration.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <span class="mt-1 inline-flex rounded-2xl bg-indigo-100 p-3 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .843-3 1.882v4.236C9 15.157 10.343 16 12 16s3-.843 3-1.882V9.882C15 8.843 13.657 8 12 8z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4 2-4 2m-6-4l-4 2 4 2"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Tailor your outreach</h3>
                        <p class="mt-2 text-sm text-slate-500">Send a short personalised note with each application—refer to what excites you about the role.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <span class="mt-1 inline-flex rounded-2xl bg-lime-100 p-3 text-lime-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h10m-6 4h6"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Schedule weekly check-ins</h3>
                        <p class="mt-2 text-sm text-slate-500">Block time to follow up on applications, connect with your network, and add new wins to your profile.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<!-- Footer -->
@include('layouts.footer')
