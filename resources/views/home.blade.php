@include('layouts.header')

@php
    $featuredCollection = $featuredPosts instanceof \Illuminate\Support\Collection
        ? $featuredPosts
        : collect($featuredPosts ?? []);
    $latestCollection = $latestPosts instanceof \Illuminate\Support\Collection
        ? $latestPosts
        : collect($latestPosts ?? []);
    $featuredCount = $featuredCollection->count();
    $latestCount = $latestCollection->count();
@endphp

<section class="relative isolate overflow-hidden bg-slate-950">
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}"
         alt="Modern professionals collaborating"
         class="absolute inset-0 h-full w-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/85 to-slate-900/70"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-24 sm:py-28 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Careers, curated
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                Discover roles faster, share your story smarter.
            </h1>
            <p class="mt-4 text-lg text-slate-200 sm:text-xl">
                ProSnap connects ambitious professionals with employers ready to hire. Publish a job-winning resume post or explore fresh roles crafted by hiring teams today.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-yellow-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 4v16m8-8H4" />
                    </svg>
                    Create my candidate profile
                </a>
                <a href="{{ route('employer.register') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-white/25 px-6 py-3 text-sm font-semibold text-white transition hover:border-yellow-300 hover:text-yellow-200">
                    Post a new role
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
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Featured resumes</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $featuredCount }}</p>
                <p class="mt-2 text-sm text-slate-200">High-impact candidate stories handpicked by our hiring partners.</p>
            </div>
            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Latest opportunities</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $latestCount }}</p>
                <p class="mt-2 text-sm text-slate-200">Fresh roles added by employers building teams right now.</p>
            </div>
            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Get started fast</p>
                <p class="mt-3 text-3xl font-semibold text-white">Minutes</p>
                <p class="mt-2 text-sm text-slate-200">Publish a polished profile or job post in less than ten minutes.</p>
            </div>
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl space-y-16 px-6 pb-20 pt-12 sm:px-8">
        <section class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <div class="grid gap-8 md:grid-cols-3">
                <div class="md:col-span-1">
                    <h2 class="text-2xl font-semibold text-slate-900">Why ProSnap?</h2>
                    <p class="mt-3 text-sm text-slate-500">
                        We bridge the gap between standout talent and teams moving fast. Whether you’re applying or hiring, ProSnap keeps everything human, focused, and efficient.
                    </p>
                </div>
                <div class="md:col-span-2 grid gap-6 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <h3 class="text-lg font-semibold text-slate-900">Structured storytelling</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Our resume posts encourage clear summaries, quantified wins, and collaboration preferences so hiring teams understand you at a glance.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <h3 class="text-lg font-semibold text-slate-900">Employer-ready insights</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            ATS checks, profile analytics, and quick messaging make it painless for employers to move great candidates forward.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <h3 class="text-lg font-semibold text-slate-900">Curated opportunities</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Browse roles with transparent expectations, salary signals, and clear collaboration style directly from employers.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <h3 class="text-lg font-semibold text-slate-900">Built for speed</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Candidates publish once, reuse everywhere. Employers import roles or author them inline, saving valuable time.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1fr,0.9fr]">
            <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-2xl font-semibold text-slate-900">How it works</h2>
                <div class="mt-8 space-y-6">
                    <div class="flex gap-4">
                        <span class="mt-1 inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-white font-semibold">1</span>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Create your profile or job post</h3>
                            <p class="mt-2 text-sm text-slate-600">
                                Structured prompts help candidates highlight their impact and employers articulate expectations.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <span class="mt-1 inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-white font-semibold">2</span>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Show up where it matters</h3>
                            <p class="mt-2 text-sm text-slate-600">
                                Posts appear across tailored feeds, keeping jobs and talent in front of decision-makers.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <span class="mt-1 inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-white font-semibold">3</span>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Connect, interview, hire</h3>
                            <p class="mt-2 text-sm text-slate-600">
                                Use messaging, ATS feedback, and profile insights to move quickly from interest to offer.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-8 text-white shadow-sm ring-1 ring-slate-800">
                <h2 class="text-2xl font-semibold">Spotlight: real outcomes</h2>
                <ul class="mt-8 space-y-5 text-sm text-slate-200">
                    <li class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="font-semibold text-white">Sara, Product Designer</p>
                        <p class="mt-1 text-sm">“TEXT”</p>
                    </li>
                    <li class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="font-semibold text-white"> Systems</p>
                        <p class="mt-1 text-sm">“TEXT”</p>
                    </li>
                    <li class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="font-semibold text-white">Victor, Engineering Lead</p>
                        <p class="mt-1 text-sm">“TEXT”</p>
                    </li>
                </ul>
            </div>
        </section>

        <section class="space-y-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Featured resumes</h2>
                    <p class="mt-1 text-sm text-slate-500">Handpicked stories from candidates actively primed for their next challenge.</p>
                </div>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                    Join as candidate
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($featuredPosts as $post)
                    @php
                        $route = route('posts.show', $post->id);
                        $when = $post->published_at ?? $post->created_at ?? null;
                        $whenStr = '';
                        if ($when instanceof \Carbon\Carbon) {
                            $whenStr = $when->diffForHumans();
                        } elseif (is_string($when)) {
                            $whenStr = \Carbon\Carbon::parse($when)->diffForHumans();
                        }
                    @endphp
                    <a href="{{ $route }}"
                       class="group flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-48 w-full overflow-hidden">
                            <img src="{{ !empty($post->image) ? asset('storage/posts/' . $post->image) : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png' }}"
                                 alt="{{ $post->title }}"
                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            <span class="absolute left-4 top-4 inline-flex items-center rounded-full bg-yellow-400 px-3 py-1 text-xs font-semibold text-slate-900">Featured</span>
                        </div>
                        <div class="flex flex-1 flex-col gap-3 p-6">
                            <p class="text-xs text-slate-400">{{ $whenStr }}</p>
                            <h3 class="text-lg font-semibold text-slate-900 group-hover:text-blue-600">{{ $post->title }}</h3>
                            <p class="text-sm text-slate-600">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->summary ?? $post->body ?? ''), 120) }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
                        <h3 class="text-lg font-semibold text-slate-900">Featured resumes will appear soon</h3>
                        <p class="mt-2 text-sm text-slate-500">Create your profile now to be considered for the spotlight.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="space-y-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Latest job posts</h2>
                    <p class="mt-1 text-sm text-slate-500">Hiring teams recently added these roles — reach out while they’re fresh.</p>
                </div>
                <a href="{{ route('employer.register') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                    Hire with ProSnap
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
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
                        $company = $post->company_name ?? $post->name ?? null;
                        $location = $post->location ?? null;
                    @endphp
                    <a href="{{ $route }}"
                       class="group flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-48 w-full overflow-hidden">
                            <img src="{{ !empty($post->image) ? asset('storage/posts/' . $post->image) : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png' }}"
                                 alt="{{ $post->title }}"
                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            <span class="absolute left-4 top-4 inline-flex items-center rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">New</span>
                        </div>
                        <div class="flex flex-1 flex-col gap-3 p-6">
                            <p class="text-xs text-slate-400">{{ $whenStr }}</p>
                            <h3 class="text-lg font-semibold text-slate-900 group-hover:text-blue-600">{{ $post->title }}</h3>
                            @if ($company || $location)
                                <p class="text-sm font-medium text-slate-500">
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
                            <p class="text-sm text-slate-600">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->body ?? ''), 110) }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
                        <h3 class="text-lg font-semibold text-slate-900">Have a role to fill?</h3>
                        <p class="mt-2 text-sm text-slate-500">Become the first to post — great candidates are browsing right now.</p>
                        <a href="{{ route('employer.register') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Get started
                        </a>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Resources for candidates</h2>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li class="flex gap-3">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-white text-xs font-semibold">1</span>
                            Build a compelling “Ideal role” section so hiring managers know exactly where you thrive.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-white text-xs font-semibold">2</span>
                            Add a case study or portfolio link to offer a deeper dive without lengthy emails.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-white text-xs font-semibold">3</span>
                            Use ATS analysis to iterate quickly and track alignment with in-demand skills.
                        </li>
                    </ul>
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Resources for employers</h2>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li class="flex gap-3">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-white text-xs font-semibold">1</span>
                            Write success metrics into your job description so candidates understand impact early.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-white text-xs font-semibold">2</span>
                            Share collaboration style and culture cues — the right match values transparency.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-white text-xs font-semibold">3</span>
                            Track applicants via ProSnap and keep your talent pipeline tidy without extra tooling.
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="rounded-3xl bg-slate-950 px-8 py-12 text-center text-white shadow-sm ring-1 ring-slate-800">
            <h2 class="text-3xl font-semibold">Ready to make your next hire or land your dream role?</h2>
            <p class="mt-3 text-sm text-slate-300">
                Join ProSnap for free, publish in minutes, and stay top-of-mind for the opportunities that matter.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                
                <a href="{{ route('employer.register') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-6 py-3 text-sm font-semibold text-white transition hover:border-yellow-300 hover:text-yellow-200">
                    Create employer account
                </a>
            </div>
        </section>
    </div>
</main>

@include('layouts.footer')
