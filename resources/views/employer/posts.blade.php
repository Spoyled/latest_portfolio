@include('layouts.header')

@php
    $imageUrl = !empty($post->image)
        ? asset('storage/posts/' . $post->image)
        : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png';

    $companyName = $post->company_name ?? $post->name ?? 'Hiring team';
    $location = $post->location ?? 'Remote friendly';
    $position = $post->position ?? 'Opportunity';

    $salaryValue = $post->salary ?? null;
    $salaryFormatted = is_numeric($salaryValue)
        ? '€' . number_format((float) $salaryValue, 0, '.', ' ')
        : 'Competitive';

    $skills = collect(preg_split('/[,;|]+/', (string) ($post->skills ?? '')))
        ->map(fn ($skill) => trim($skill))
        ->filter()
        ->values();

    $publishedAt = $post->published_at ?? $post->created_at ?? null;
    if (is_string($publishedAt)) {
        $publishedAt = \Carbon\Carbon::parse($publishedAt);
    }
    $publishedHuman = $publishedAt instanceof \Carbon\Carbon ? $publishedAt->diffForHumans() : null;
    $publishedDate = $publishedAt instanceof \Carbon\Carbon ? $publishedAt->format('M d, Y') : null;

    $isClosed = !empty($post->closed_at) || (isset($post->is_active) && (int) $post->is_active === 0);
@endphp

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ $imageUrl }}"
         alt="{{ $post->title }}"
         class="absolute inset-0 h-full w-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/90 to-slate-900/70"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-16 sm:py-20 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Open position
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                {{ $post->title }}
            </h1>
            <p class="mt-4 text-lg text-slate-200 sm:text-xl">
                Join {{ $companyName }} as we continue to grow. We are looking for a motivated {{ $position }} who thrives in collaborative environments.
            </p>
        </div>

        <div class="flex flex-wrap gap-6 text-sm text-slate-300">
            <div class="inline-flex items-center gap-2">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-yellow-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Hiring team</p>
                    <p class="text-sm font-semibold text-white">{{ $companyName }}</p>
                </div>
            </div>

            <div class="inline-flex items-center gap-2">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-yellow-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17.657 16.657L13.414 12l4.243-4.243M10.586 16.657L6.343 12l4.243-4.243" />
                    </svg>
                </span>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Location</p>
                    <p class="text-sm font-semibold text-white">{{ $location }}</p>
                </div>
            </div>

            <div class="inline-flex items-center gap-2">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-yellow-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 8c-1.657 0-3 .843-3 1.882v4.236C9 15.157 10.343 16 12 16s3-.843 3-1.882V9.882C15 8.843 13.657 8 12 8z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17 8V5a2 2 0 00-2-2H9a2 2 0 00-2 2v3" />
                    </svg>
                </span>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Salary range</p>
                    <p class="text-sm font-semibold text-white">{{ $salaryFormatted }}</p>
                </div>
            </div>

            @if ($publishedDate)
                <div class="inline-flex items-center gap-2">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-yellow-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
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

        @if ($errors->any())
            <div class="rounded-3xl border border-rose-200 bg-rose-50 p-5 text-sm font-semibold text-rose-700 shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid gap-10 lg:grid-cols-[minmax(0,2fr),minmax(0,1fr)]">
            <article class="flex flex-col gap-10 rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <section class="space-y-6">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Role summary</h2>
                        <p class="mt-3 text-sm text-slate-600 leading-relaxed">{{ $post->body }}</p>
                    </div>

                    @if ($skills->isNotEmpty())
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Key skills</h3>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($skills as $skill)
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Location</h3>
                            <p class="mt-2 text-sm text-slate-600">{{ $location }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Contract type</h3>
                            <p class="mt-2 text-sm text-slate-600">{{ $post->contract_type ?? 'Full-time' }}</p>
                        </div>
                    </div>

                    @if(!empty($post->additional_links))
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Learn more</h3>
                            <a href="{{ $post->additional_links }}" target="_blank" rel="noopener noreferrer"
                               class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M13.828 10.172a4 4 0 015.657 5.657l-3.536 3.536a4 4 0 01-5.657-5.657m-.707-4.95a4 4 0 115.657 5.657L11.95 18.364a4 4 0 11-5.657-5.657l3.536-3.536z" />
                                </svg>
                                Visit company site
                            </a>
                        </div>
                    @endif
                </section>

                @if(auth()->check() && !auth('employer')->check())
                    <section class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">Ready to apply?</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Submit your latest CV so {{ $companyName }} can connect with you directly. No cover letter required.
                        </p>
                        <div class="mt-6">
                            @if($hasApplied)
                                <p class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M5 13l4 4L19 7" />
                                    </svg>
                                    Application received — we will be in touch soon.
                                </p>
                            @elseif($isClosed)
                                <p class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-4 py-2 text-sm font-semibold text-rose-600">
                                    This role is no longer accepting applications.
                                </p>
                            @else
                                <button id="applyButton"
                                        type="button"
                                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                                        onclick="openApplyModal()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M12 4v16m8-8H4" />
                                    </svg>
                                    Apply now
                                </button>
                            @endif
                        </div>
                    </section>
                @endif
            </article>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Role insights</h3>
                    <dl class="mt-4 space-y-4 text-sm text-slate-600">
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-semibold text-slate-700">Applicants</dt>
                            <dd>{{ $applicantsCount }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-semibold text-slate-700">Status</dt>
                            <dd class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $isClosed ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                                {{ $isClosed ? 'Closed' : 'Accepting candidates' }}
                            </dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-semibold text-slate-700">Applications this week</dt>
                            <dd>{{ $post->applications_this_week ?? 'Growing' }}</dd>
                        </div>
                    </dl>
                </div>

                @if(auth('employer')->check() && auth('employer')->id() === ($post->employer_id ?? null))
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Employer actions</h3>
                        <div class="mt-4 space-y-3 text-sm">
                            <a href="{{ route('posts.applicants', $post->id) }}"
                               class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700 transition hover:border-yellow-300 hover:bg-white">
                                View applicants
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            <a href="{{ route('employer.posts.edit', $post->id) }}"
                               class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700 transition hover:border-yellow-300 hover:bg-white">
                                Edit posting
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-.707.44l-4 1.333a1 1 0 01-1.262-1.262l1.333-4a2 2 0 01.44-.707z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M19 20H5" />
                                </svg>
                            </a>
                            <div class="rounded-2xl border border-slate-200 bg-rose-50 px-4 py-3">
                                @if(!$isClosed)
                                    <form method="POST" action="{{ route('posts.close', $post->id) }}" class="inline-flex w-full items-center justify-between">
                                        @csrf
                                        <span class="text-sm font-semibold text-rose-600">Close this position</span>
                                        <button class="rounded-full bg-rose-600 px-4 py-1 text-xs font-semibold text-white transition hover:bg-rose-700">
                                            Confirm
                                        </button>
                                    </form>
                                @else
                                    <p class="text-sm font-semibold text-rose-600">
                                        Closed @if(!empty($post->closed_at)) on {{ \Carbon\Carbon::parse($post->closed_at)->format('M d, Y') }} @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </aside>
        </div>

    </div>
</main>

@if(auth()->check() && !auth('employer')->check() && !$hasApplied && !$isClosed)
    <div id="applyModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 px-4 py-6">
        <div class="w-full max-w-lg rounded-3xl bg-white p-8 shadow-xl">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Submit your CV</h2>
                    <p class="mt-1 text-sm text-slate-500">Upload a tailored CV or continue with the one already on your profile.</p>
                </div>
                <button type="button" onclick="closeApplyModal()" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200">
                    <span class="sr-only">Close</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('posts.apply', $postModel->id ?? $post->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                @csrf
                <div>
                    <label for="cv_file" class="text-sm font-semibold text-slate-700">Upload CV (optional)</label>
                    <input type="file"
                           name="cv_file"
                           id="cv_file"
                           accept=".pdf,.doc,.docx"
                           class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <p class="mt-2 text-xs text-slate-400">
                        Uploading is optional if you have already saved a CV to your profile.
                    </p>
                    @if ($errors->has('cv_file'))
                        <p class="mt-2 text-xs font-semibold text-rose-600">{{ $errors->first('cv_file') }}</p>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button"
                            class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-300"
                            onclick="closeApplyModal()">
                        Cancel
                    </button>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M5 13l4 4L19 7" />
                        </svg>
                        Submit application
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

@if (($openApply ?? false) && auth()->check() && !auth('employer')->check())
    <script>
        document.addEventListener('DOMContentLoaded', () => openApplyModal());
    </script>
@endif

<script>
function openApplyModal() {
    const modal = document.getElementById('applyModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeApplyModal() {
    const modal = document.getElementById('applyModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

</script>

@include('layouts.footer')
