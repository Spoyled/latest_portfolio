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
                <a href="{{ route('home') }}" class="text-2xl font-semibold text-white hover:text-yellow-500">
                    Pro<span class="text-yellow-400">Snap</span>
                </a>
                <nav class="hidden sm:flex space-x-4">
                    <a class="hover:text-yellow-400 text-sm text-white" href="{{ route('home') }}">Home</a>
                </nav>
            </div>
        </div>
    </header>

<div class="relative w-full h-screen">
    <!-- Full-page Background Image -->
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}" alt="ProSnap Image" class="absolute inset-0 w-full h-full object-cover">

    <!-- Overlay Content (Centered Registration Form) -->
    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-60">
        <div class="bg-white bg-opacity-90 p-8 rounded-lg shadow-lg w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-4">
                <div class="text-gray-800 font-semibold text-3xl">
                    <span class="text-black">Pro</span><span class="text-yellow-500">Snap</span>
                </div>
            </div>

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" />

            <!-- Registration Form -->
            <form method="POST" action="{{ route('employer.register') }}">
                @csrf

                <div>
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <div class="mt-4">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('employer.login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-button class="ml-4">
                        {{ __('Register') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>
