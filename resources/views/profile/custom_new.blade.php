@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-red-700 font-semibold">Please fix the following errors:</p>
                        <ul class="mt-2 list-disc list-inside text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Profile Header Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <!-- Header with Gradient -->
            <div class="h-32 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800"></div>
            
            <div class="px-6 pb-6">
                <form action="{{ Auth::guard('employer')->check() ? route('employer.custom.profile.update') : route('custom.profile.update') }}" 
                      method="POST" 
                      enctype="multipart/form-data" 
                      class="space-y-6">
                    @csrf
                    
                    <!-- Profile Picture Section -->
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-16">
                        <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                            <!-- Profile Picture with Upload Overlay -->
                            <div class="relative group">
                                <div class="relative">
                                    @if($user->profile_photo_path)
                                        <img src="{{ asset('storage/profile_photos/' . $user->profile_photo_path) }}" 
                                             class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-2xl" 
                                             alt="Profile Picture"
                                             id="profilePreview">
                                    @else
                                        <img src="{{ asset('storage/images/profile1.png') }}" 
                                             class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-2xl" 
                                             alt="Default Profile Picture"
                                             id="profilePreview">
                                    @endif
                                    
                                    <!-- Upload Overlay -->
                                    <label for="profile_photo" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </label>
                                </div>
                                <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(event)">
                            </div>
                            
                            <!-- User Info -->
                            <div class="text-center md:text-left">
                                <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                                <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                                <span class="inline-block mt-2 px-4 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                    {{ Auth::guard('employer')->check() ? '🏢 Employer Account' : '👤 Employee Account' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Form Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <!-- Name/Company Name -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="inline w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ Auth::guard('employer')->check() ? 'Company Name' : 'Full Name' }}
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ $user->name }}" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>

                        @if(Auth::guard('employer')->check())
                            <!-- Company Description for Employers -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="company_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="inline w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Company Description
                                </label>
                                <textarea name="company_description" 
                                          id="company_description" 
                                          rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                                          placeholder="Tell us about your company, its mission, and values...">{{ $user->company_description ?? '' }}</textarea>
                            </div>
                        @endif

                        <!-- Password Change Section -->
                        <div class="col-span-1">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="inline w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                New Password
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                   placeholder="Leave blank to keep current">
                            <p class="mt-1 text-xs text-gray-500">Leave blank if you don't want to change</p>
                        </div>

                        <div class="col-span-1">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="inline w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Confirm Password
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                   placeholder="Confirm new password">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4 border-t">
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-8 py-3 rounded-lg shadow-lg hover:from-blue-700 hover:to-indigo-800 transition-all transform hover:scale-105 font-semibold">
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

