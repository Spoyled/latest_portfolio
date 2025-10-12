@include('layouts.header')

@php
    $totalPosts = $allPosts->count();
    $heroTitle = auth('employer')->check()
        ? 'Discover talented professionals ready for your next opening.'
        : 'Find opportunities that match your goals.';
    $heroSubtitle = auth('employer')->check()
        ? 'Filter by expertise, review highlighted resumes, and reach out directly to promising talent.'
        : 'Use smart filters to surface roles aligned with your skills, values, and preferred locations.';
@endphp

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}"
         alt="Professional workspace"
         class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/75 to-slate-900/60"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-16 sm:py-20 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Curated {{ auth('employer')->check() ? 'resumes' : 'roles' }}
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                {{ $heroTitle }}
            </h1>
            <p class="mt-3 text-lg text-slate-200 sm:text-xl">
                {{ $heroSubtitle }}
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Live listings</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $totalPosts }}</p>
                <p class="mt-2 text-sm text-slate-200">Freshly updated profiles tailored to the ProSnap community.</p>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Smart filters</p>
                <p class="mt-3 text-2xl font-semibold text-white">Refine in seconds</p>
                <p class="mt-2 text-sm text-slate-200">Combine keywords, locations, and roles to surface the right matches.</p>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Stay up to date</p>
                <p class="mt-3 text-2xl font-semibold text-white">Check back daily</p>
                <p class="mt-2 text-sm text-slate-200">Our community adds new highlights throughout the week.</p>
            </div>
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl space-y-12 px-6 pb-20 pt-12 sm:px-8">
        <section class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">
                        {{ auth('employer')->check() ? 'Filter resumes' : 'Filter opportunities' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Apply one or more filters to uncover the most relevant {{ auth('employer')->check() ? 'profiles' : 'roles' }} for you.
                    </p>
                </div>
                <a href="{{ route('all_posts.index') }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                    Reset filters
                </a>
            </div>

            <form action="{{ route('all_posts.index') }}" method="GET"
                  class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @unless(auth('employer')->check())
                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Minimum salary (€)</span>
                        <input type="number"
                               name="salary_min"
                               value="{{ $salaryMin }}"
                               min="0"
                               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                               placeholder="e.g. 2500">
                    </label>
                @endunless

                <label class="flex flex-col gap-2">
                    <span class="text-sm font-semibold text-slate-700">{{ auth('employer')->check() ? 'Name or expertise' : 'Role or keyword' }}</span>
                    <input type="text"
                           name="name_filter"
                           value="{{ $nameFilter }}"
                           class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                           placeholder="{{ auth('employer')->check() ? 'Product design, portfolio, etc.' : 'Product designer, React, etc.' }}">
                </label>

                <label class="flex flex-col gap-2">
                    <span class="text-sm font-semibold text-slate-700">Location</span>
                    <input type="text"
                           name="location_filter"
                           value="{{ $locationFilter }}"
                           class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                           placeholder="Remote, Berlin, Vilnius">
                </label>

                <label class="flex flex-col gap-2">
                    <span class="text-sm font-semibold text-slate-700">{{ auth('employer')->check() ? 'Desired role' : 'Position type' }}</span>
                    <input type="text"
                           name="position_filter"
                           value="{{ $positionFilter }}"
                           class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                           placeholder="Engineering, Marketing, etc.">
                </label>

                <div class="md:col-span-2 lg:col-span-4">
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Apply filters
                    </button>
                </div>
            </form>
        </section>

        <section class="space-y-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">
                        {{ auth('employer')->check() ? 'Highlighted resumes' : 'Latest opportunities' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Browse curated cards with quick context, then dive into each listing for full details.
                    </p>
                </div>
                @unless(auth('employer')->check())
                    <a href="{{ route('make_post.index') }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                        Share your profile
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </a>
                @endunless
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($allPosts as $post)
                    @php
                        $route = auth('employer')->check()
                            ? route('employer.posts.show', $post->id)
                            : route('posts.show', $post->id);
                        $when = $post->published_at ?? $post->created_at ?? null;
                        $whenStr = '';
                        if ($when instanceof \Carbon\Carbon) {
                            $whenStr = auth('employer')->check()
                                ? $when->format('M d, Y')
                                : $when->diffForHumans();
                        } elseif (is_string($when)) {
                            $parsed = \Carbon\Carbon::parse($when);
                            $whenStr = auth('employer')->check()
                                ? $parsed->format('M d, Y')
                                : $parsed->diffForHumans();
                        }
                        $imageUrl = !empty($post->image)
                            ? asset('storage/posts/' . $post->image)
                            : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png';
                        $summarySource = $post->summary
                            ?? $post->short_description
                            ?? $post->description
                            ?? $post->body
                            ?? null;
                        $summary = is_string($summarySource)
                            ? \Illuminate\Support\Str::limit(strip_tags($summarySource), 120)
                            : 'Open the listing to review responsibilities, tools, and next steps.';
                        $company = $post->company_name ?? $post->company ?? ($post->name ?? null);
                        $location = $post->location ?? null;
                        $salary = $post->salary ?? null;
                    @endphp
                    <a href="{{ $route }}"
                       class="group flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-48 w-full overflow-hidden">
                            <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            <span class="absolute left-4 top-4 inline-flex items-center rounded-full bg-yellow-400 px-3 py-1 text-xs font-semibold text-slate-900">
                                {{ $whenStr ?: 'Just in' }}
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col gap-4 p-6">
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
                            <div class="mt-auto flex items-center justify-between text-xs font-semibold text-slate-500">
                                @if ($salary && !auth('employer')->check())
                                    <span>From €{{ number_format((float)$salary, 0, '.', ' ') }}</span>
                                @endif
                                <span class="inline-flex items-center gap-1 text-blue-600 group-hover:text-blue-700">
                                    View details
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center shadow-sm">
                        <h3 class="text-xl font-semibold text-slate-900">No results match your filters yet</h3>
                        <p class="mt-2 text-sm text-slate-500">Try adjusting your filters or check back soon for new additions.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</main>

@include('layouts.footer')
