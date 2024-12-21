<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script> <!-- Alpine.js for interactivity -->
    <style>
        .sticky-nav {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: white;
            box-shadow: 0 4px 2px -4px gray;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-[#f0f4f8]">
    <!-- Header (sticky and doesn't interfere with the layout) -->
    <header class="sticky-nav h-16 px-4 bg-blue-900 border-b border-yellow-500 w-full">
        <div class="container mx-auto flex justify-between items-center h-full">
            <!-- Logo and Navigation Links -->
            <div class="flex items-center space-x-8">
                <a href="{{ auth('employer')->check() ? route('employer.dashboard') : route('HomePage') }}" 
                class="text-2xl font-semibold text-white hover:text-yellow-500">
                    Pro<span class="text-yellow-400">Snap</span>
                </a>
                <nav class="hidden sm:flex space-x-4">
                    @if(auth('employer')->check())
                        <a class="hover:text-yellow-400 text-sm text-white" href="{{ route('employer.portfolios') }}">My Posts</a>
                        <a class="hover:text-yellow-400 text-sm text-white" href="{{ route('employer.all_posts') }}">All Posts</a>
                        <a class="hover:text-yellow-400 text-sm text-white" href="{{ route('employer.make_post') }}">Make a Post</a>
                    @else
                        <a class="hover:text-yellow-400 text-sm text-white" href="{{ route('HomePage') }}">Home</a>
                        <a class="hover:text-yellow-400 text-sm text-white" href="{{ route('portfolios.index') }}">My Posts</a>
                        <a class="hover:text-yellow-400 text-sm text-white" href="{{ route('all_posts.index') }}">All Posts</a>
                        <a class="hover:text-yellow-400 text-sm text-white" href="{{ route('make_post.index') }}">Make a Post</a>
                    @endif
                </nav>


            </div>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                <button @click="open = ! open" class="flex items-center focus:outline-none">
                @if (auth('employer')->check() && auth('employer')->user()->profile_photo_path)
                    {{-- Display employer's profile photo --}}
                    <img src="{{ asset('storage/profile_photos/' . auth('employer')->user()->profile_photo_path) }}" class="rounded-full h-20 w-20 object-cover" alt="Profile Picture">
                @elseif (auth()->check() && auth()->user()->profile_photo_path)
                    {{-- Display default user profile photo if logged in --}}
                    <img src="{{ asset('storage/profile_photos/' . auth()->user()->profile_photo_path) }}" class="rounded-full h-20 w-20 object-cover" alt="Profile Picture">
                @else
                    {{-- Display a fallback image --}}
                    <img src="{{ asset('storage/images/profile1.png') }}" class="rounded-full h-20 w-20 object-cover" alt="Profile Picture">
                @endif

                </button>
                <div x-show="open" class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                    @php
                        $profileRoute = auth('employer')->check() 
                            ? route('employer.custom.profile.show') 
                            : route('custom.profile.show');
                    @endphp
                    <a href="{{ $profileRoute }}" class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-100">Profile</a>
                    <a href="#" @click="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-100">Logout</a>
                </div>
            </div>

            <!-- Logout Form -->
            <form id="logout-form" method="POST" 
                action="{{ auth('employer')->check() ? route('employer.logout') : route('logout') }}" 
                style="display: none;">
                @csrf
            </form>

        </div>
    </header>