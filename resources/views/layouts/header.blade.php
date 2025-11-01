<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

@php
    $isEmployer = auth('employer')->check();
    $isUser = auth()->check();
    $isAuthenticated = $isEmployer || $isUser;

    if ($isEmployer) {
        $navLinks = [
            ['label' => 'Dashboard', 'route' => route('employer.dashboard')],
            ['label' => 'My Posts', 'route' => route('employer.portfolios')],
            ['label' => 'All Posts', 'route' => route('employer.all_posts')],
            ['label' => 'Post a Job', 'route' => route('employer.make_post')],
        ];
    } elseif ($isUser) {
        $navLinks = [
            ['label' => 'Home', 'route' => route('HomePage')],
            ['label' => 'My Posts', 'route' => route('portfolios.index')],
            ['label' => 'All Posts', 'route' => route('all_posts.index')],
            ['label' => 'Create Post', 'route' => route('make_post.index')],
        ];
    } else {
        $navLinks = [
            ['label' => 'Home', 'route' => route('home')],
            ['label' => 'All Posts', 'route' => route('all_posts.index')],
        ];
    }

    if ($isUser && optional(auth()->user())->is_admin) {
        $navLinks[] = ['label' => 'Admin Dashboard', 'route' => 'http://193.219.91.103:1332/admin/dashboard', 'external' => true];
        $navLinks[] = ['label' => 'phpMyAdmin', 'route' => 'http://193.219.91.103:8303/', 'external' => true];
    }

    $profileRoute = null;
    if ($isEmployer) {
        $profileRoute = route('employer.custom.profile.show');
    } elseif ($isUser) {
        $profileRoute = route('custom.profile.show');
    }

    if ($isEmployer) {
        $ctaRoute = route('employer.make_post');
        $ctaLabel = 'Post a Job';
    } elseif ($isUser) {
        $ctaRoute = route('make_post.index');
        $ctaLabel = 'Share a resume post';
    }
@endphp

<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <header
        x-data="{ open: false }"
        class="sticky top-0 z-50 border-b border-slate-800 bg-slate-950/95 shadow-[0_10px_40px_rgba(15,23,42,0.35)] backdrop-blur">
        <div class="mx-auto flex max-w-6xl flex-col px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between">
                <div class="flex items-center gap-8">
                    <a href="{{ $isEmployer ? route('employer.dashboard') : ($isUser ? route('HomePage') : route('home')) }}"
                       class="flex items-center gap-2 text-2xl font-semibold text-white transition hover:text-yellow-300">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-yellow-400/20 text-yellow-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 3l2.09 6.26H20l-5.17 3.76L16.91 21 12 16.98 7.09 21l1.08-7.98L3 9.26h5.91z" />
                            </svg>
                        </span>
                        <span>Pro<span class="text-yellow-300">Snap</span></span>
                    </a>

                    <nav class="hidden items-center gap-6 text-sm font-medium text-slate-300 lg:flex">
                        @foreach ($navLinks as $link)
                            <a
                                @if (!empty($link['external'])) target="_blank" rel="noopener noreferrer" @endif
                                href="{{ $link['route'] }}"
                                class="transition hover:text-yellow-300">
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                <div class="flex items-center gap-4">


                    @if ($isAuthenticated)
                        <div x-data="{ menuOpen: false }" class="relative">
                            <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false"
                                    class="flex items-center gap-3 rounded-full border border-slate-700/70 bg-slate-900/60 px-2 py-1 pr-3 text-left text-xs font-medium text-slate-200 shadow-inner transition hover:border-yellow-300/60">
                                <span class="inline-flex items-center justify-center rounded-full bg-slate-800 p-0.5">
                                    @if ($isEmployer && optional(auth('employer')->user())->profile_photo_path)
                                        <img src="{{ asset('storage/profile_photos/' . auth('employer')->user()->profile_photo_path) }}"
                                             alt="Profile picture"
                                             class="h-10 w-10 rounded-full object-cover" />
                                    @elseif ($isUser && optional(auth()->user())->profile_photo_path)
                                        <img src="{{ asset('storage/profile_photos/' . auth()->user()->profile_photo_path) }}"
                                             alt="Profile picture"
                                             class="h-10 w-10 rounded-full object-cover" />
                                    @else
                                        <img src="{{ asset('storage/images/profile1.png') }}"
                                             alt="Profile picture"
                                             class="h-10 w-10 rounded-full object-cover" />
                                    @endif
                                </span>
                                <span class="hidden sm:block">
                                    <span class="block text-xs uppercase tracking-wide text-slate-400">Signed in</span>
                                    <span class="block text-sm font-semibold text-white">
                                        {{ $isEmployer ? optional(auth('employer')->user())->name : optional(auth()->user())->name }}
                                    </span>
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="menuOpen"
                                 x-transition
                                 class="absolute right-0 mt-3 w-52 overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/95 shadow-xl ring-1 ring-slate-700/60">
                                @if ($profileRoute)
                                    <a href="{{ $profileRoute }}"
                                       class="block px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-slate-800/80">
                                        View profile
                                    </a>
                                @endif
                                <button
                                    class="block w-full px-4 py-3 text-left text-sm font-semibold text-rose-300 transition hover:bg-rose-500/10"
                                    @click.prevent="document.getElementById('logout-form').submit();">
                                    Sign out
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="hidden items-center gap-3 lg:flex">
                            <a href="{{ route('login') }}"
                               class="rounded-xl border border-slate-700/70 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:border-yellow-300 hover:text-yellow-200">
                                Log in
                            </a>
                            <a href="{{ route('register') }}"
                               class="rounded-xl bg-yellow-400 px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-yellow-300">
                                Join ProSnap
                            </a>
                        </div>
                    @endif

                    <button
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-700/70 bg-slate-900/60 text-slate-200 transition hover:border-yellow-300/60 lg:hidden"
                        @click="open = !open">
                        <span class="sr-only">Toggle navigation</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <div x-cloak x-show="open" x-transition
                 class="grid gap-4 pb-6 pt-4 text-sm font-medium text-slate-200 lg:hidden">
                @foreach ($navLinks as $link)
                    <a
                        @if (!empty($link['external'])) target="_blank" rel="noopener noreferrer" @endif
                        href="{{ $link['route'] }}"
                        class="rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-3 transition hover:border-yellow-300/40 hover:bg-slate-900">
                        {{ $link['label'] }}
                    </a>
                @endforeach
                
                @unless ($isAuthenticated)
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-2xl border border-slate-700 px-4 py-3 text-slate-200 transition hover:border-yellow-300 hover:text-yellow-200">
                        Log in
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-yellow-400 px-4 py-3 text-slate-900 transition hover:bg-yellow-300">
                        Join ProSnap
                    </a>
                @endunless
            </div>
        </div>
    </header>

    @if ($isAuthenticated)
        <form id="logout-form"
              method="POST"
              action="{{ $isEmployer ? route('employer.logout') : route('logout') }}"
              class="hidden">
            @csrf
        </form>
    @endif
