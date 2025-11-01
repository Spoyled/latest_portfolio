@include('layouts.header')

@php
    $imageUrl = !empty($post->image)
        ? asset('storage/posts/' . $post->image)
        : asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg');

    $companyName = $post->company_name
        ?? data_get($post, 'employer.name')
        ?? 'Hiring team';

    $location = $post->location
        ?? data_get($post, 'employer.location')
        ?? 'Remote friendly';

    $applicants = $post->applicants
        ->sortByDesc(fn ($applicant) => $applicant->pivot->created_at ?? $applicant->pivot->updated_at ?? now());

    $totalApplicants = $applicants->count();
    $recruitedCount = $applicants->filter(fn ($applicant) => (bool) ($applicant->pivot->recruited ?? false))->count();
    $declinedCount = $applicants->filter(fn ($applicant) => (bool) ($applicant->pivot->declined ?? false))->count();
    $pendingCount = max($totalApplicants - $recruitedCount - $declinedCount, 0);
@endphp

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ $imageUrl }}"
         alt="{{ $post->title }}"
         class="absolute inset-0 h-full w-full object-cover opacity-30">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/90 to-slate-900/65"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-8 px-6 py-16 sm:py-20 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Candidate pipeline
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                Applicants for {{ $post->title }}
            </h1>
            <p class="mt-4 text-lg text-slate-200 sm:text-xl">
                Review profiles, progress conversations, and move the right talent forward for {{ $companyName }}.
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
                              d="M12 11c1.657 0 3-1.567 3-3.5S13.657 4 12 4 9 5.567 9 7.5 10.343 11 12 11z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19.5 19c0-3.037-3.358-5.5-7.5-5.5S4.5 15.963 4.5 19" />
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
                              d="M8 7h8M8 11h8m-3 4h3" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2z" />
                    </svg>
                </span>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Applications</p>
                    <p class="text-sm font-semibold text-white">{{ $totalApplicants }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="bg-slate-100 text-slate-900">
    <div class="mx-auto max-w-6xl space-y-10 px-6 pb-20 pt-12 sm:px-8">
        <section class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">In review</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $pendingCount }}</p>
                <p class="mt-3 text-xs text-slate-500">Awaiting a decision</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Recruited</p>
                <p class="mt-2 text-3xl font-semibold text-emerald-700">{{ $recruitedCount }}</p>
                <p class="mt-3 text-xs text-emerald-600">Joined the team</p>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">Declined</p>
                <p class="mt-2 text-3xl font-semibold text-rose-700">{{ $declinedCount }}</p>
                <p class="mt-3 text-xs text-rose-600">Not moving forward</p>
            </div>
        </section>

        @if ($totalApplicants === 0)
            <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M8 11h8m-8 4h5m-2 7h6a2 2 0 002-2v-5" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M16 17h1a3 3 0 003-3V6a2 2 0 00-2-2h-3" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 21H7a2 2 0 01-2-2v-5a3 3 0 013-3h1" />
                    </svg>
                </div>
                <h2 class="mt-6 text-xl font-semibold text-slate-900">No applicants yet</h2>
                <p class="mt-3 text-sm text-slate-500">Share {{ $post->title }} with your network to bring fresh candidates into your pipeline.</p>
            </div>
        @else
            <div class="space-y-8">
                @foreach ($applicants as $applicant)
                    @php
                        $status = 'in_review';
                        $statusLabel = 'In review';
                        $statusClasses = 'inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700';

                        if ($applicant->pivot->recruited ?? false) {
                            $status = 'recruited';
                            $statusLabel = 'Recruited';
                            $statusClasses = 'inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700';
                        } elseif ($applicant->pivot->declined ?? false) {
                            $status = 'declined';
                            $statusLabel = 'Declined';
                            $statusClasses = 'inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700';
                        }

                        $appliedAt = optional($applicant->pivot->created_at);
                        $lastUpdatedAt = optional($applicant->pivot->updated_at);
                    @endphp

                    <article class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                        <div class="flex flex-col gap-6 md:flex-row md:justify-between md:gap-10">
                            <div class="flex-1 space-y-4">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <h2 class="text-xl font-semibold text-slate-900">
                                        {{ $applicant->name }}
                                    </h2>
                                    <span class="{{ $statusClasses }}">
                                        <span class="h-2 w-2 rounded-full bg-current"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="space-y-1 text-sm">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</p>
                                        <a href="mailto:{{ $applicant->email }}" class="text-sm font-medium text-blue-600 transition hover:text-blue-700">
                                            {{ $applicant->email }}
                                        </a>
                                    </div>
                                    <div class="space-y-1 text-sm">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Applied</p>
                                        <p class="text-sm text-slate-600">
                                            {{ $appliedAt ? $appliedAt->format('M d, Y') : '—' }}
                                            @if ($appliedAt)
                                                <span class="text-xs text-slate-400">· {{ $appliedAt->diffForHumans() }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="space-y-1 text-sm">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Last update</p>
                                        <p class="text-sm text-slate-600">
                                            {{ $lastUpdatedAt ? $lastUpdatedAt->format('M d, Y') : '—' }}
                                            @if ($lastUpdatedAt && $appliedAt && !$lastUpdatedAt->equalTo($appliedAt))
                                                <span class="text-xs text-slate-400">· {{ $lastUpdatedAt->diffForHumans() }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="space-y-1 text-sm">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Profile</p>
                                        <p class="text-sm text-slate-600">
                                            {{ $applicant->professional_headline ?? 'Updated via submission' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 md:w-56">
                                @if(!empty($applicant->pivot->cv_path))
                                    <a href="{{ asset('storage/cvs/' . $applicant->pivot->cv_path) }}"
                                       target="_blank"
                                       class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12l4-4m-4 4L8 8m4 4V4" />
                                        </svg>
                                        Download CV
                                    </a>
                                @else
                                    <span class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-500">
                                        No CV attached
                                    </span>
                                @endif

                                @if ($status === 'in_review')
                                    <form method="POST"
                                          action="{{ route('applicants.recruit', [$post->id, $applicant->id]) }}"
                                          class="decision-form"
                                          data-action="recruit"
                                          data-post-id="{{ $post->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M5 13l4 4L19 7" />
                                            </svg>
                                            Mark as recruited
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('applicants.decline', [$post->id, $applicant->id]) }}"
                                          class="decision-form"
                                          data-action="decline"
                                          data-post-id="{{ $post->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Decline
                                        </button>
                                    </form>
                                @else
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-center text-xs font-semibold text-slate-500">
                                        Decision recorded
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.decision-form').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const action = form.dataset.action;
            const postId = form.dataset.postId;
            const verb = action === 'recruit' ? 'mark this applicant as recruited' : 'decline this applicant';

            const firstStep = await Swal.fire({
                title: `Confirm decision`,
                text: `Are you sure you want to ${verb}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Yes, continue',
            });

            if (!firstStep.isConfirmed) {
                return;
            }

            const secondStep = await Swal.fire({
                title: 'Keep the role open?',
                text: 'If you no longer need applicants, we can close the job post for you.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Keep collecting',
                cancelButtonText: 'Close the post',
            });

            if (!secondStep.isConfirmed) {
                const tokenInput = form.querySelector('input[name="_token"]');
                const csrfToken = tokenInput ? tokenInput.value : '';

                try {
                    const response = await fetch(`/posts/${postId}/close`, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({}),
                    });

                    let responseData = {};
                    try {
                        responseData = await response.clone().json();
                    } catch (_) {
                        responseData = { status: response.status };
                    }

                    if (!response.ok) {
                        throw new Error(responseData?.error || 'Failed to close the post.');
                    }

                    await Swal.fire('Post closed', 'The job post is now closed to new applicants.', 'success');
                    form.submit();
                } catch (error) {
                    console.error('Post close error:', error);
                    Swal.fire('Error', error.message || 'Something went wrong while closing the post.', 'error');
                }
            } else {
                form.submit();
            }
        });
    });
});
</script>

@include('layouts.footer')
