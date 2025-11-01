@include('layouts.header')

<section class="relative isolate overflow-hidden bg-slate-900">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/85 to-slate-900/65"></div>
    <div class="relative mx-auto flex max-w-5xl flex-col gap-6 px-6 py-16 sm:py-20">
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300">CV Verification</p>
        <h1 class="text-3xl font-semibold text-white sm:text-4xl">Verification Successful</h1>
        <p class="text-lg text-slate-200 sm:text-xl">This CV snapshot is authentic and matches the source of record stored on ProSnap.</p>
    </div>
</section>

<main class="bg-slate-100">
    <div class="px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            <div class="rounded-2xl bg-white shadow-2xl">
                <div class="border-b border-slate-200 bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-5 sm:px-8">
                    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-white">Document provenance confirmed</h2>
                            <p class="text-sm text-emerald-100">Matched against the ProSnap ledger for {{ optional($cvVersion->user)->name ?? 'unknown user' }}.</p>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1 text-sm font-semibold text-white backdrop-blur">Verified</span>
                    </div>
                </div>

                <div class="space-y-10 px-6 py-8 sm:px-8">
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-600">Snapshot metadata</h3>
                            <dl class="mt-4 space-y-2 text-sm text-slate-700">
                                <div class="flex justify-between"><dt class="font-medium">Candidate</dt><dd>{{ optional($cvVersion->user)->name ?? 'Unknown' }}</dd></div>
                                <div class="flex justify-between"><dt class="font-medium">Template</dt><dd>{{ $cvVersion->template }}</dd></div>
                                @if(!empty($meta['version_number']))
                                    <div class="flex justify-between"><dt class="font-medium">Version</dt><dd>{{ $meta['version_number'] }}</dd></div>
                                @endif
                                <div class="flex justify-between"><dt class="font-medium">Generated at</dt><dd>{{ \Carbon\Carbon::parse($meta['generated_at'] ?? $cvVersion->created_at)->format('Y-m-d H:i') }}</dd></div>
                                <div class="flex justify-between"><dt class="font-medium">Recorded at</dt><dd>{{ $cvVersion->created_at->format('Y-m-d H:i') }}</dd></div>
                            </dl>
                        </div>
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Verification ID</h3>
                            <p class="mt-3 break-all font-mono text-xs text-emerald-900">{{ strtoupper($verificationHash) }}</p>
                            <p class="mt-3 text-sm text-emerald-800">Match this code with the badge embedded in the CV footer or QR label. Anyone with the link can re-validate authenticity.</p>
                            <a href="{{ route('cv.verify', ['hash' => $verificationHash]) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-900">
                                Re-run verification
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </a>
                        </div>
                    </div>

                    @if(!empty($analysis['score']))
                        <section class="rounded-xl border border-slate-200 bg-white p-6">
                            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">ATS Snapshot</h3>
                                    <p class="text-sm text-slate-500">Captured at generation time to preserve the scoring context employers saw.</p>
                                </div>
                                <div class="inline-flex items-baseline gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2">
                                    <span class="text-2xl font-bold text-emerald-700">{{ $analysis['score'] }}</span>
                                    <span class="text-sm text-emerald-600">/ 100</span>
                                </div>
                            </header>

                            @if(!empty($analysis['breakdown']))
                                <div class="mt-6 grid gap-4 md:grid-cols-2">
                                    @foreach($analysis['breakdown'] as $row)
                                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-4">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="font-semibold text-slate-800">{{ $row['category'] ?? 'Category' }}</span>
                                                @if(isset($row['score'], $row['weight']))
                                                    <span class="text-slate-600">{{ $row['score'] }}/{{ $row['weight'] }}</span>
                                                @endif
                                            </div>
                                            @if(!empty($row['notes']))
                                                <p class="mt-2 text-xs text-slate-500">{{ $row['notes'] }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-6 grid gap-6 md:grid-cols-2">
                                @if(!empty($analysis['highlights']))
                                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                                        <h4 class="text-sm font-semibold text-emerald-700">Strengths detected</h4>
                                        <ul class="mt-3 space-y-2 text-sm text-emerald-900">
                                            @foreach($analysis['highlights'] as $item)
                                                <li>{{ $item['message'] ?? $item }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(!empty($analysis['warnings']))
                                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                                        <h4 class="text-sm font-semibold text-amber-700">Opportunities to improve</h4>
                                        <ul class="mt-3 space-y-2 text-sm text-amber-900">
                                            @foreach($analysis['warnings'] as $item)
                                                <li>{{ $item['message'] ?? $item }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif

                    <section class="rounded-xl border border-slate-200 bg-slate-50 p-6">
                        <h3 class="text-base font-semibold text-slate-900">How to double-check authenticity</h3>
                        <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-slate-700">
                            <li>Open the downloaded CV and confirm the verification badge or QR code matches the ID above.</li>
                            <li>If the candidate sends an updated CV, request a fresh verification link to avoid stale versions.</li>
                        </ol>
                        <p class="mt-4 text-xs text-slate-500">Optional but recommended: share this verification link with hiring managers so they can independently confirm authenticity.</p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>

@include('layouts.footer')
