@php
    $isEmployer = auth('employer')->check();
    $primaryLinks = $isEmployer
        ? [
            ['label' => 'Dashboard', 'route' => route('employer.dashboard')],
            ['label' => 'My Posts', 'route' => route('employer.portfolios')],
            ['label' => 'All Posts', 'route' => route('employer.all_posts')],
            ['label' => 'Make a Post', 'route' => route('employer.make_post')],
        ]
        : [
            ['label' => 'Home', 'route' => route('HomePage')],
            ['label' => 'My Posts', 'route' => route('portfolios.index')],
            ['label' => 'All Posts', 'route' => route('all_posts.index')],
            ['label' => 'Make a Post', 'route' => route('make_post.index')],
        ];

    if (auth()->check() && auth()->user()->is_admin) {
        $primaryLinks[] = ['label' => 'Admin Dashboard', 'route' => 'http://193.219.91.103:1332/admin/dashboard', 'external' => true];
        $primaryLinks[] = ['label' => 'phpMyAdmin', 'route' => 'http://193.219.91.103:8303/', 'external' => true];
    }
@endphp

<footer class="mt-auto border-t border-slate-800 bg-slate-950/90">
    <div class="mx-auto flex max-w-6xl flex-col gap-12 px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-[1.2fr,1fr,1fr]">
            <div>
                <a href="{{ $isEmployer ? route('employer.dashboard') : route('HomePage') }}"
                   class="inline-flex items-center gap-2 text-xl font-semibold text-white transition hover:text-yellow-300">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-yellow-400/20 text-yellow-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 3l2.09 6.26H20l-5.17 3.76L16.91 21 12 16.98 7.09 21l1.08-7.98L3 9.26h5.91z" />
                        </svg>
                    </span>
                    <span>Pro<span class="text-yellow-300">Snap</span></span>
                </a>
                <p class="mt-4 max-w-md text-sm text-slate-400">
                    ProSnap connects ambitious professionals with opportunities that recognise their impact.
                    Keep your profile sharp, share your biggest wins, and stay visible to teams actively hiring.
                </p>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-300">Navigate</h3>
                <ul class="mt-4 space-y-2 text-sm text-slate-400">
                    @foreach ($primaryLinks as $link)
                        <li>
                            <a
                                @if (!empty($link['external'])) target="_blank" rel="noopener noreferrer" @endif
                                href="{{ $link['route'] }}"
                                class="inline-flex items-center gap-2 transition hover:text-yellow-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-300" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 5l7 7-7 7" />
                                </svg>
                                <span>{{ $link['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-300">Stay connected</h3>
                <ul class="mt-4 space-y-3 text-sm text-slate-400">
                    <li class="flex items-center gap-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-900/80 text-yellow-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M16 8c0-1.1-.9-2-2-2H6a2 2 0 00-2 2v11l4-4h6a2 2 0 002-2V8z" />
                            </svg>
                        </span>
                        <span>support@prosnap.io</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-900/80 text-yellow-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M18 13.37V18a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2h3.28a1 1 0 00.7-.29l1.42-1.42a1 1 0 01.7-.29H16a2 2 0 012 2v3.37z" />
                            </svg>
                        </span>
                        <span>Vilnius</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-900/80 text-yellow-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M7 8h10M7 12h4m-2 8a10 10 0 110-20 10 10 0 010 20z" />
                            </svg>
                        </span>
                        <a href="{{ $isEmployer ? route('employer.make_post') : route('make_post.index') }}"
                           class="transition hover:text-yellow-300">Share your next highlight</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col gap-4 border-t border-slate-800 pt-6 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ now()->year }} ProSnap.
            <div class="flex gap-4">

            </div>
        </div>
    </div>
</footer>

@include('components.support-bot')

</body>
</html>
