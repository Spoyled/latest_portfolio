@include('layouts.header')

@php
    $imageUrl = !empty($post->image)
        ? asset('storage/posts/' . $post->image)
        : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png';

    $ownerName = $post->name ?? 'ProSnap member';

    $publishedAt = $post->published_at ?? $post->created_at ?? null;
    if (is_string($publishedAt)) {
        $publishedAt = \Carbon\Carbon::parse($publishedAt);
    }
    $publishedHuman = $publishedAt instanceof \Carbon\Carbon ? $publishedAt->diffForHumans() : null;
    $publishedDate = $publishedAt instanceof \Carbon\Carbon ? $publishedAt->format('M d, Y') : null;

    $skills = collect(preg_split('/[,;|]+/', (string) ($post->skills ?? '')))
        ->map(fn ($skill) => trim($skill))
        ->filter()
        ->values();

    $hasResume = !empty($post->resume);
    $hasAdditionalLink = !empty($post->additional_links);
    $additionalLinkHost = $hasAdditionalLink ? parse_url($post->additional_links, PHP_URL_HOST) : null;

    $resumeSections = \App\Support\ResumeBlueprint::parse($post->body ?? '');
    $summaryText = $post->summary ?? $resumeSections['summary'] ?? null;
    $sectionMeta = [
        'summary' => [
            'title' => 'Professional Snapshot',
            'hint'  => null,
        ],
        'highlights' => [
            'title' => 'Recent Wins',
            'hint'  => 'Examples of measurable impact and shipped work.',
        ],
        'ideal_role' => [
            'title' => 'Ideal Next Role',
            'hint'  => 'The roles, teams, and missions that excite them.',
        ],
        'collaboration' => [
            'title' => 'Collaboration Style',
            'hint'  => 'How they communicate, lead, and partner with teams.',
        ],
        'availability' => [
            'title' => 'Availability & Logistics',
            'hint'  => 'Start dates, location preferences, or ways of working.',
        ],
    ];
@endphp

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ $imageUrl }}"
         alt="{{ $post->title }}"
         class="absolute inset-0 h-full w-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/90 to-slate-900/70"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-16 sm:py-20 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Candidate spotlight
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                {{ $post->title }}
            </h1>
            <p class="mt-4 text-lg text-slate-200 sm:text-xl">
                {{ $summaryText ?? 'Handcrafted experience highlights from ' . $ownerName . '.' }}
            </p>
        </div>

        <div class="flex flex-wrap gap-6 text-sm text-slate-300">
            <div class="inline-flex items-center gap-2">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-yellow-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Presented by</p>
                    <p class="text-sm font-semibold text-white">{{ $ownerName }}</p>
                </div>
            </div>

            @if ($publishedDate)
                <div class="inline-flex items-center gap-2">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-yellow-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Published</p>
                        <p class="text-sm font-semibold text-white">{{ $publishedDate }} @if($publishedHuman) · {{ $publishedHuman }} @endif</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl space-y-12 px-6 pb-24 pt-12 sm:px-8">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-sm font-semibold text-emerald-700 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('status'))
            <div class="rounded-3xl border border-blue-200 bg-blue-50 p-5 text-sm font-semibold text-blue-700 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-3xl border border-rose-200 bg-rose-50 p-5 text-sm font-semibold text-rose-700 shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid gap-10 lg:grid-cols-[minmax(0,2fr),minmax(0,1fr)]">
            <article class="flex flex-col gap-10 rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <figure class="overflow-hidden rounded-3xl bg-slate-100">
                    <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="h-80 w-full object-cover">
                </figure>

                <section class="space-y-6">
                    @foreach ($sectionMeta as $key => $meta)
                        @php $content = trim((string) ($resumeSections[$key] ?? '')); @endphp
                        @if ($content !== '')
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">{{ $meta['title'] }}</h3>
                                <p class="mt-3 text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $content }}</p>
                                @if (!empty($meta['hint']))
                                    <p class="mt-2 text-xs text-slate-400">{{ $meta['hint'] }}</p>
                                @endif
                            </div>
                        @endif
                    @endforeach

                    @if (!empty($post->education))
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Education</h3>
                            <p class="mt-2 text-sm text-slate-600">{{ $post->education }}</p>
                        </div>
                    @endif

                    @if ($skills->isNotEmpty())
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Core expertise</h3>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($skills as $skill)
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </section>

                <div class="flex flex-wrap gap-3">
                    @if ($hasAdditionalLink)
                        <a href="{{ $post->additional_links }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-yellow-300 hover:text-slate-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M13.828 10.172a4 4 0 015.657 5.657l-3.536 3.536a4 4 0 01-5.657-5.657m-.707-4.95a4 4 0 115.657 5.657L11.95 18.364a4 4 0 11-5.657-5.657l3.536-3.536z" />
                            </svg>
                            {{ $additionalLinkHost ?? 'Portfolio link' }}
                        </a>
                    @endif

                    @if ($hasResume)
                        <a href="{{ asset('storage/resumes/' . $post->resume) }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 4v12m0 0l-3-3m3 3l3-3m-9 3a9 9 0 1118 0 9 9 0 01-18 0z" />
                            </svg>
                            Download resume
                        </a>
                    @endif

                    @if(auth()->check() && auth()->id() === ($post->user_id ?? null))
                        <a href="{{ route('posts.edit', $post->id) }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-yellow-300 hover:text-slate-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-.707.44l-4 1.333a1 1 0 01-1.262-1.262l1.333-4a2 2 0 01.44-.707z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M19 20H5" />
                            </svg>
                            Update post
                        </a>
                    @endif
                </div>
            </article>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Snapshot</h3>
                    <dl class="mt-4 space-y-4 text-sm text-slate-600">
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-semibold text-slate-700">Visibility</dt>
                            <dd>{{ $post->visibility ?? 'Public' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-semibold text-slate-700">Applications received</dt>
                            <dd>{{ $applicantsCount ?? 0 }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-semibold text-slate-700">Status</dt>
                            <dd class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                Open to opportunities
                            </dd>
                        </div>
                        @if ($hasResume)
                            <div class="flex items-start justify-between gap-4">
                                <dt class="font-semibold text-slate-700">Resume</dt>
                                <dd class="text-slate-700">Attached</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Next steps</h3>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-3">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-yellow-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4" />
                                </svg>
                            </span>
                            Add tangible metrics that show how your work moved the needle.
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-yellow-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4" />
                                </svg>
                            </span>
                            Upload supporting visuals or links to shipped work.
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-yellow-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4" />
                                </svg>
                            </span>
                            Share this profile with mentors for feedback before interviews.
                        </li>
                    </ul>
                </div>
            </aside>
        </div>

    </div>
</main>

@include('layouts.footer')
