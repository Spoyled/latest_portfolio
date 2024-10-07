<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script> <!-- Alpine.js for interactivity -->
</head>

<body class="font-light antialiased">

    <header class="bg-white border-b border-gray-100 py-3 px-6">
        <div class="container mx-auto flex justify-between items-center">

            <!-- Logo and Navigation Links -->
            <div class="flex items-center space-x-8">
                <a href="{{ route('dashboard') }}" class="text-xl font-semibold text-gray-800 hover:text-yellow-500">
                    Pro<span class="text-yellow-500">Snap</span>
                </a>
                <nav class="hidden sm:flex space-x-4">
                    <a class="text-sm text-gray-500 hover:text-yellow-500" href="{{ route('dashboard') }}">Home</a>
                    <a class="text-sm text-gray-500 hover:text-yellow-500" href="{{ url('/portfolios') }}">My Posts</a>
                    <a class="text-sm text-gray-500 hover:text-yellow-500" href="{{ url('/all_posts') }}">All Posts</a>
                    <a class="text-sm text-gray-500 hover:text-yellow-500" href="{{ url('/make_post') }}">Make a Post</a>
                </nav>
            </div>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                <button @click="open = ! open" class="flex items-center focus:outline-none">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="h-8 w-8 rounded-full">
                    <span class="hidden sm:block ml-2 text-sm font-medium">{{ Auth::user()->name }}</span>
                </button>

                <div x-show="open" class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <!-- Other links -->
                    <a href="#" @click="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Logout
                    </a>
                </div>
            </div>

            <!-- Logout Form -->
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>

        </div>
    </header>