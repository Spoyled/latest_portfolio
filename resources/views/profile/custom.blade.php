@include('layouts.header')

@php
    $profileUser = $user ?? auth()->user();
@endphp

<section class="relative isolate overflow-hidden bg-slate-900">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/85 to-slate-900/65"></div>
    <div class="relative mx-auto flex max-w-7xl flex-col gap-8 px-6 py-16 sm:py-20 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Your profile hub
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                {{ $profileUser->name ?? 'Your profile' }}
            </h1>
            <p class="mt-3 text-lg text-slate-200 sm:text-xl">
                Keep your portfolio current, manage CV versions, and adapt your story for every opportunity.
            </p>
        </div>
    </div>
</section>

<main class="bg-slate-100 text-slate-900">
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animain">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-amber-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.721-1.36 3.486 0l6.518 11.593C19.02 15.987 18.158 17 16.812 17H3.188c-1.346 0-2.208-1.013-1.449-2.308L8.257 3.1zm1.743-.597a1 1 0 00-1.746 0L1.736 14.094C1.117 15.215 1.884 16.5 3.188 16.5h13.624c1.304 0 2.071-1.285 1.452-2.406L9.999 2.502z" clip-rule="evenodd"/><path d="M10 7a1 1 0 011 1v3a1 1 0 11-2 0V8a1 1 0 011-1z"/><path d="M9 13a1 1 0 102 0 1 1 0 10-2 0z"/></svg>
                    <p class="text-amber-700 font-medium">{{ session('warning') }}</p>
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

    @if(Auth::guard('employer')->check())
        <!-- Employer Statistics Dashboard -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Job Post Analytics
                </h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Job Posts -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Total Posts</p>
                                <p class="text-4xl font-bold text-blue-900 mt-2">{{ $totalJobPosts ?? 0 }}</p>
                            </div>
                            <div class="bg-blue-200 rounded-full p-4">
                                <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Active Job Posts -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-green-600 uppercase tracking-wide">Active</p>
                                <p class="text-4xl font-bold text-green-900 mt-2">{{ $activeJobPosts ?? 0 }}</p>
                            </div>
                            <div class="bg-green-200 rounded-full p-4">
                                <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Applications Received -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-purple-600 uppercase tracking-wide">Applications</p>
                                <p class="text-4xl font-bold text-purple-900 mt-2">{{ $applicationsReceived ?? 0 }}</p>
                            </div>
                            <div class="bg-purple-200 rounded-full p-4">
                                <svg class="w-8 h-8 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Closed Job Posts -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Closed</p>
                                <p class="text-4xl font-bold text-gray-900 mt-2">{{ $closedJobPosts ?? 0 }}</p>
                            </div>
                            <div class="bg-gray-200 rounded-full p-4">
                                <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.5fr),minmax(0,1fr)]">
            <section class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Company snapshot</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Refresh your story so candidates understand the mission, culture, and teams they'll join.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-500">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        {{ __('Active employer since :year', ['year' => optional($user->created_at)->format('Y') ?? now()->format('Y')]) }}
                    </span>
                </div>

                <p class="mt-6 text-sm leading-relaxed text-slate-600">
                    {{ $user->company_description ?? 'Add a short, high-signal description so applicants understand your hiring focus and what sets your team apart.' }}
                </p>

                <div class="mt-8 grid gap-6 sm:grid-cols-3">
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Total roles launched</p>
                        <p class="mt-3 text-2xl font-semibold text-blue-900">{{ $totalJobPosts }}</p>
                        <p class="mt-1 text-xs text-blue-600">{{ $applicationsReceived }} total applicants</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Currently open</p>
                        <p class="mt-3 text-2xl font-semibold text-emerald-800">{{ $activeJobPosts }}</p>
                        <p class="mt-1 text-xs text-emerald-600">Keep momentum by nudging conversations weekly.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">Recently closed</p>
                        <p class="mt-3 text-2xl font-semibold text-slate-900">{{ $closedJobPosts }}</p>
                        <p class="mt-1 text-xs text-slate-500">Maintain contact for future roles.</p>
                    </div>
                </div>

                @if(isset($activeRoles) && $activeRoles->isNotEmpty())
                    <div class="mt-8">
                        <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Currently hiring for</h4>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            @foreach($activeRoles as $role)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $role->title }}</p>
                                            <p class="text-xs text-slate-500">{{ $role->location ?? 'Remote friendly' }}</p>
                                        </div>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Open
                                        </span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                                        <span>{{ $role->applicants_count }} applicants</span>
                                        <span>Posted {{ optional($role->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mt-8 rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500">
                        Highlight your upcoming roles here. <a href="{{ route('employer.make_post') }}" class="font-semibold text-blue-600 hover:text-blue-700">Share a new opening</a> to keep candidates engaged.
                    </div>
                @endif
            </section>

            <div class="space-y-8">
                <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Recent postings</h3>
                    <p class="mt-1 text-sm text-slate-500">Check in on performance and keep descriptions aligned with what candidates value.</p>

                    <div class="mt-5 space-y-4">
                        @forelse(($recentJobPosts ?? collect()) as $role)
                            @php
                                $isOpen = empty($role->closed_at);
                                $statusLabel = $isOpen ? 'Active' : 'Closed';
                                $statusClasses = $isOpen
                                    ? 'inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-700'
                                    : 'inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600';
                            @endphp
                            <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $role->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $role->location ?? 'Remote friendly' }}</p>
                                    <p class="mt-2 text-xs text-slate-400">Published {{ optional($role->created_at)->format('M d, Y') ?? '—' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="{{ $statusClasses }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $isOpen ? 'bg-emerald-500' : 'bg-slate-500' }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                    <p class="mt-2 text-xs font-semibold text-slate-500">{{ $role->applicants_count }} applicants</p>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-6 text-sm text-slate-500">
                                Your latest job posts will surface here with quick glance metrics once they’re live.
                            </p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Latest pipeline activity</h3>
                    <p class="mt-1 text-sm text-slate-500">Track the most recent moves across your roles.</p>

                    @if(isset($latestApplicantActivities) && $latestApplicantActivities->isNotEmpty())
                        <ol class="mt-5 space-y-4">
                            @foreach($latestApplicantActivities as $activity)
                                @php
                                    $statusLabel = 'Applied';
                                    $badgeClasses = 'bg-sky-100 text-sky-700';
                                    $dotClasses = 'bg-sky-500';

                                    if ($activity->recruited) {
                                        $statusLabel = 'Recruited';
                                        $badgeClasses = 'bg-emerald-100 text-emerald-700';
                                        $dotClasses = 'bg-emerald-500';
                                    } elseif ($activity->declined) {
                                        $statusLabel = 'Declined';
                                        $badgeClasses = 'bg-rose-100 text-rose-700';
                                        $dotClasses = 'bg-rose-500';
                                    }

                                    $createdAt = $activity->created_at instanceof \Carbon\Carbon ? $activity->created_at : null;
                                @endphp
                                <li class="relative pl-6">
                                    <span class="absolute left-0 top-2 h-3 w-3 rounded-full {{ $dotClasses }}"></span>
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $activity->user_name }}</p>
                                                <p class="text-xs text-slate-500">{{ $activity->post_title }}</p>
                                            </div>
                                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-semibold {{ $badgeClasses }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                        <div class="mt-3 text-xs text-slate-500">
                                            <span>{{ $activity->user_email ?? 'Email unavailable' }}</span>
                                            @if(!empty($activity->user_location ?? null))
                                                <span class="mx-2 text-slate-400">•</span>
                                                <span>{{ $activity->user_location ?? '' }}</span>
                                            @endif
                                        </div>
                                        @if(!empty($activity->user_summary ?? null))
                                            <p class="mt-2 text-xs text-slate-500">
                                                {{ \Illuminate\Support\Str::limit($activity->user_summary ?? '', 110) }}
                                            </p>
                                        @endif
                                        <p class="mt-3 text-[11px] uppercase tracking-wide text-slate-400">
                                            {{ $createdAt ? $createdAt->format('M d, Y · H:i') : '—' }}
                                            @if($createdAt)
                                                <span class="ml-1 text-slate-500">({{ $createdAt->diffForHumans() }})</span>
                                            @endif
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="mt-5 rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-6 text-sm text-slate-500">
                            Once applications arrive, you’ll see the latest updates and decisions here.
                        </p>
                    @endif
                </section>
            </div>
        </div>
    @endif


    @if(!Auth::guard('employer')->check())
        @if($appliedPosts->count())

        
        <hr class="my-8 border-t-2 border-gray-200">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Jobs You've Applied To</h2>

            <ul class="space-y-4">
                @foreach($appliedPosts as $post)
                    <li class="border p-4 rounded shadow-sm @if($post->closed_at) bg-red-50 @else bg-green-50 @endif">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $post->title }}</h3>
                        <p class="text-gray-600">Location: {{ $post->location ?? 'N/A' }}</p>
                        <p class="text-gray-600">Position: {{ $post->position ?? 'N/A' }}</p>

                        @if($post->closed_at)
                            <span class="text-red-600 font-semibold">This job post is closed</span>
                        @else
                            <span class="text-green-600 font-semibold">Still Open</span>
                        @endif

                        <div class="mt-2">
                            <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600 underline">
                                View Post
                            </a>
                            @if($post->pivot->cv_path)
                                <span class="ml-2 text-gray-500 text-sm">(CV: {{ $post->pivot->cv_path }})</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <hr class="my-8 border-t-2 border-gray-200">

        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Jobs You've Applied To</h2>
            <p class="text-gray-600">You haven’t applied to any jobs yet.</p>
        </div>
    @endif


        <!-- CV Management Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    CV Management
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Upload/Download -->
                    <div class="space-y-6">
                        <!-- Current CV Display -->
                        @if($user->cv_path)
                            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 flex flex-col items-center justify-center text-center">
                                <div class="bg-blue-200 rounded-full p-4 mb-4">
                                    <svg class="w-12 h-12 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-sm text-gray-600 font-semibold mb-2">Current CV</p>
                                <p class="text-base font-bold text-gray-900 mb-4">{{ basename($user->cv_path) }}</p>
                                <a href="{{ route('profile.download-cv') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </a>
                            </div>
                        @endif

                        <!-- Upload New CV Card -->
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-200 rounded-xl p-6 flex flex-col items-center justify-center text-center">
                             <div class="bg-emerald-200 rounded-full p-4 mb-4">
                                <svg class="w-12 h-12 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Upload New CV</h3>
                            <p class="text-sm text-gray-600 mb-4">PDF, DOC, DOCX (Max: 2MB)</p>
                            <form action="{{ route('profile.upload-cv') }}" method="POST" enctype="multipart/form-data" class="w-full">
                                @csrf
                                <input type="file" name="cv_file" id="cv_file" accept=".pdf,.doc,.docx" class="hidden" onchange="this.form.submit()">
                                <label for="cv_file" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white font-semibold rounded-lg transition-all cursor-pointer">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    Choose File
                                </label>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column: Generator and Versions -->
                    <div class="space-y-6">
                        @php $versionLimitReached = $cvVersions->count() >= 3; @endphp
                        <!-- Generate CV Card -->
                        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-xl p-6 flex flex-col items-center justify-center text-center">
                            <div class="bg-purple-200 rounded-full p-4 mb-4">
                                <svg class="w-12 h-12 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Generate Professional CV</h3>
                            <p class="text-sm text-gray-600 mb-4">Create a custom CV with your details</p>
                            <button onclick="openCVModal()" @if($versionLimitReached) disabled class="bg-slate-300 text-slate-500 px-6 py-2 rounded-full font-semibold cursor-not-allowed" @else class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 shadow-lg" @endif>
                                Generate Professional CV
                            </button>
                            @if($versionLimitReached)
                                <p class="mt-3 text-xs text-rose-600">Limit reached: delete one of your saved CVs to create a new draft.</p>
                            @endif
                        </div>

                        <!-- CV Versions List -->
                        <div id="cv-versions-list" class="mt-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Your CV Versions</h3>
                            @if($cvVersions->count())
                                <ul class="space-y-4">
                                    @foreach($cvVersions as $version)
                                        <li class="border p-4 rounded-lg shadow-sm bg-gray-50">
                                            <div class="flex justify-between items-center gap-4 flex-wrap">
                                                <div>
                                                    <p class="font-semibold text-gray-800">Version {{ $version->version_number }} ({{ $version->template }})</p>
                                                    <p class="text-sm text-gray-600">Generated: {{ $version->created_at->format('Y-m-d H:i') }}</p>
                                                    @if($version->notes)
                                                        <p class="text-xs text-gray-500 mt-1">{{ $version->notes }}</p>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    <a href="{{ route('profile.download-cv', ['version' => $version->id]) }}" class="text-blue-600 hover:underline text-sm font-semibold">Download</a>
                                                    <form method="POST" action="{{ route('profile.cv-versions.destroy', $version) }}" onsubmit="return confirm('Delete this CV version?');" class="flex">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-600">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <p class="mt-3 text-xs text-slate-500">Store up to three versions. Delete an older version to free a slot.</p>
                            @else
                                <p class="text-gray-600">You do not have any generated CV versions yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CV Generation Modal -->
        <div id="cvModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-indigo-700 px-6 py-4 flex justify-between items-center rounded-t-2xl">
                    <h2 class="text-2xl font-bold text-white">Generate CV</h2>
                    <button onclick="closeCVModal()" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form id="cv-generator-form" action="{{ route('profile.generate-cv') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    <section class="bg-slate-50 border border-slate-200 rounded-xl p-6 space-y-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-800">Profile Overview</h3>
                            <span class="text-xs font-medium uppercase tracking-wide text-slate-500">Required</span>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="professional_headline" class="block text-sm font-semibold text-slate-700 mb-1">Professional Headline</label>
                                <input type="text" name="professional_headline" id="professional_headline" placeholder="Senior Laravel Engineer" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="target_role" class="block text-sm font-semibold text-slate-700 mb-1">Target Role</label>
                                <input type="text" name="target_role" id="target_role" placeholder="Engineering Lead" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-semibold text-slate-700 mb-1">Preferred Location</label>
                                <input type="text" name="location" id="location" value="{{ old('location', $user->location ?? '') }}" placeholder="Vilnius, Lithuania" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1">Contact Phone</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="+370 600 00000" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <p class="mt-1 text-xs text-slate-500">Use international format so the ATS can parse it.</p>
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-semibold text-slate-700 mb-1">Version Notes</label>
                                <input type="text" name="notes" id="notes" placeholder="Internal draft, ready for review..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label for="about_me" class="block text-sm font-semibold text-slate-700 mb-1">Professional Summary</label>
                            <textarea name="about_me" id="about_me" rows="5" required placeholder="Capture your value proposition, focus areas, and leadership strengths in 3-4 sentences." class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                    </section>

                    <section class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800">Core Skills</h3>
                                <p class="text-sm text-slate-500">Group your technical and interpersonal strengths. Use the level or category fields to provide extra context.</p>
                            </div>
                            <button type="button" onclick="addSkill()" class="text-sm font-medium text-blue-600 hover:text-blue-700">+ Add skill</button>
                        </div>
                        <div id="skills-container" class="space-y-3">
                            <div class="skill-row grid gap-3 md:grid-cols-3">
                                <input type="text" name="skills[0][name]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Skill (e.g. Laravel)">
                                <input type="text" name="skills[0][level]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Level (e.g. Advanced)">
                                <input type="text" name="skills[0][category]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Category (e.g. Backend)">
                            </div>
                        </div>
                    </section>

                    <section class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800">Experience</h3>
                                <p class="text-sm text-slate-500">Highlight roles, responsibilities, and measurable achievements for each position.</p>
                            </div>
                            <button type="button" onclick="addExperience()" class="text-sm font-medium text-blue-600 hover:text-blue-700">+ Add experience</button>
                        </div>
                        <div id="experience-container" class="space-y-6"></div>
                    </section>

                    <section class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800">Education</h3>
                                <p class="text-sm text-slate-500">List degrees, bootcamps, or relevant academic achievements.</p>
                            </div>
                            <button type="button" onclick="addEducation()" class="text-sm font-medium text-blue-600 hover:text-blue-700">+ Add education</button>
                        </div>
                        <div id="education-container" class="space-y-4"></div>
                    </section>

                    <section class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-800">Projects</h3>
                            <button type="button" onclick="addProject()" class="text-sm font-medium text-blue-600 hover:text-blue-700">+ Add project</button>
                        </div>
                        <div id="projects-container" class="space-y-4"></div>
                    </section>

                    <section class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-800">Certifications &amp; Awards</h3>
                            <button type="button" onclick="addCertification()" class="text-sm font-medium text-blue-600 hover:text-blue-700">+ Add certification</button>
                        </div>
                        <div id="certifications-container" class="space-y-4"></div>
                    </section>

                    <section class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-800">Languages</h3>
                            <button type="button" onclick="addLanguage()" class="text-sm font-medium text-blue-600 hover:text-blue-700">+ Add language</button>
                        </div>
                        <div id="spoken-languages-container" class="space-y-4"></div>
                    </section>

                    <section class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-800">Interests</h3>
                            <button type="button" onclick="addInterest()" class="text-sm font-medium text-blue-600 hover:text-blue-700">+ Add interest</button>
                        </div>
                        <div id="interests-container" class="space-y-3"></div>
                    </section>

                    <section class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-800">References</h3>
                            <button type="button" onclick="addReference()" class="text-sm font-medium text-blue-600 hover:text-blue-700">+ Add reference</button>
                        </div>
                        <div id="references-container" class="space-y-4"></div>
                    </section>

                    <section class="bg-slate-50 border border-slate-200 rounded-xl p-6 space-y-4">
                        <h3 class="text-lg font-semibold text-slate-800">Output Settings</h3>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="template" class="block text-sm font-semibold text-slate-700 mb-1">Template</label>
                                <select name="template" id="template" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="Minimal">Minimal</option>
                                    <option value="Business">Business</option>
                                    <option value="Tech">Tech</option>
                                </select>
                            </div>
                            <div class="flex items-center">
                                <p class="text-xs text-slate-500">Exports are delivered as Microsoft Word (.docx). You can convert to PDF after download if needed.</p>
                            </div>
                        </div>
                    </section>

                    @if($versionLimitReached)
                        <p class="text-sm text-rose-600 text-right">You have reached the maximum of 3 stored CVs. Delete one to generate another.</p>
                    @endif

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                        <button type="button" onclick="closeCVModal()" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-lg">Cancel</button>
                        <button type="button" onclick="analyzeCv()" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg">ATS Analysis</button>
                        <button type="submit" @if($versionLimitReached) disabled class="bg-slate-300 text-slate-500 cursor-not-allowed px-8 py-3 rounded-lg font-semibold" @else class="bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 text-white px-8 py-3 rounded-lg font-semibold shadow-lg" @endif>Generate CV</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ATS Report Modal -->
        <div id="atsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-700 px-6 py-4 flex justify-between items-center rounded-t-2xl">
                    <h2 class="text-2xl font-bold text-white">ATS Analysis Report</h2>
                    <button onclick="closeAtsModal()" class="text-white hover:text-gray-200">&times;</button>
                </div>
                <div id="ats-report" class="p-6">
                    <!-- ATS content will be injected here -->
                </div>
                <div class="flex justify-end gap-3 p-4 border-t">
                    <button type="button" onclick="closeAtsModal()" class="px-6 py-2 bg-gray-200 rounded-lg">Close</button>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>

<script>
// Profile image preview
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('profilePreview');
        preview.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}

// CV Modal functions
function openCVModal() {
    document.getElementById('cvModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCVModal() {
    document.getElementById('cvModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openAtsModal() {
    document.getElementById('atsModal').classList.remove('hidden');
}

function closeAtsModal() {
    document.getElementById('atsModal').classList.add('hidden');
}

// Dynamic form fields
let skillIndex = 0;
let experienceIndex = 0;
let educationIndex = 0;
let projectIndex = 0;
let certificationIndex = 0;
let spokenLanguageIndex = 0;
let interestIndex = 0;
let referenceIndex = 0;

document.addEventListener('DOMContentLoaded', () => {
    addSkill();
    addExperience();
    addEducation();
    addProject();
    addCertification();
    addLanguage();
    addInterest();
    addReference();
});

function addSkill() {
    const container = document.getElementById('skills-container');
    const index = skillIndex++;
    const row = document.createElement('div');
    row.className = 'skill-row space-y-2';
    row.innerHTML = `
        <div class="grid gap-3 md:grid-cols-3">
            <input type="text" name="skills[${index}][name]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Skill (e.g. Docker)">
            <input type="text" name="skills[${index}][level]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Level (e.g. Intermediate)">
            <input type="text" name="skills[${index}][category]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Category (e.g. DevOps)">
        </div>
        <div class="flex justify-end">
            <button type="button" class="text-xs font-medium text-red-500 hover:text-red-600" onclick="removeSkillRow(this)">Remove</button>
        </div>`;
    container.appendChild(row);
}

function removeSkillRow(button) {
    const wrapper = button.closest('.skill-row');
    if (wrapper) {
        wrapper.remove();
    }
}

function addExperience() {
    const container = document.getElementById('experience-container');
    const index = experienceIndex++;
    const block = document.createElement('div');
    block.className = 'experience-block border border-slate-200 rounded-lg p-4 space-y-4';
    block.dataset.index = index;
    block.innerHTML = `
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-semibold text-slate-700">Role ${index + 1}</h4>
            <button type="button" class="text-xs font-medium text-red-500 hover:text-red-600" onclick="removeExperience(this)">Remove</button>
        </div>
        <div class="grid gap-3 md:grid-cols-2">
            <input type="text" name="experience[${index}][title]" placeholder="Role / Title" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <input type="text" name="experience[${index}][company]" placeholder="Company" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <input type="text" name="experience[${index}][location]" placeholder="Location" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <div class="grid gap-3 md:grid-cols-2">
                <input type="date" name="experience[${index}][start_date]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <input type="date" name="experience[${index}][end_date]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Role Overview</label>
            <textarea name="experience[${index}][description]" rows="3" placeholder="Summarise responsibilities, scope, and stack." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
        </div>
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-slate-700">Key achievements</p>
                <button type="button" class="text-xs font-medium text-blue-600 hover:text-blue-700" onclick="addAchievement(this)">+ Add achievement</button>
            </div>
            <div class="space-y-2" data-achievements>
                <div class="flex gap-2">
                    <input type="text" name="experience[${index}][achievements][]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Achievement or metric (e.g. Increased API throughput by 40%)">
                </div>
            </div>
        </div>`;
    container.appendChild(block);
}

function removeExperience(button) {
    const block = button.closest('.experience-block');
    if (block) {
        block.remove();
    }
}


function addAchievement(button) {
    const block = button.closest('.experience-block');
    if (!block) return;
    const index = block.dataset.index;
    const container = block.querySelector('[data-achievements]');
    const row = document.createElement('div');
    row.className = 'flex gap-2';
    row.innerHTML = `
        <input type="text" name="experience[${index}][achievements][]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Achievement or metric">
        <button type="button" class="text-xs font-medium text-red-500 hover:text-red-600" onclick="removeAchievement(this)">Remove</button>`;
    container.appendChild(row);
}

function removeAchievement(button) {
    const wrapper = button.closest('.flex');
    if (wrapper) {
        wrapper.remove();
    }
}

function addEducation() {
    const container = document.getElementById('education-container');
    const index = educationIndex++;
    const block = document.createElement('div');
    block.className = 'education-block border border-slate-200 rounded-lg p-4 space-y-3';
    block.innerHTML = `
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-semibold text-slate-700">Program ${index + 1}</h4>
            <button type="button" class="text-xs font-medium text-red-500 hover:text-red-600" onclick="removeEducation(this)">Remove</button>
        </div>
        <div class="grid gap-3 md:grid-cols-2">
            <input type="text" name="education[${index}][degree]" placeholder="Degree / Program" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <input type="text" name="education[${index}][institution]" placeholder="Institution" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <input type="number" name="education[${index}][completion_year]" placeholder="Year" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <input type="text" name="education[${index}][notes]" placeholder="Highlights / GPA" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        </div>`;
    container.appendChild(block);
}

function removeEducation(button) {
    const block = button.closest('.education-block');
    if (block) {
        block.remove();
    }
}

function addProject() {
    const container = document.getElementById('projects-container');
    const index = projectIndex++;
    const block = document.createElement('div');
    block.className = 'project-block border border-slate-200 rounded-lg p-4 space-y-3';
    block.innerHTML = `
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-semibold text-slate-700">Project ${index + 1}</h4>
            <button type="button" class="text-xs font-medium text-red-500 hover:text-red-600" onclick="removeProject(this)">Remove</button>
        </div>
        <input type="text" name="projects[${index}][name]" placeholder="Project name" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        <textarea name="projects[${index}][description]" rows="3" placeholder="What was the goal? What technology or results did you deliver?" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
        <input type="text" name="projects[${index}][link]" placeholder="Link (GitHub, case study, demo)" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
    `;
    container.appendChild(block);
}

function removeProject(button) {
    const block = button.closest('.project-block');
    if (block) {
        block.remove();
    }
}

function addCertification() {
    const container = document.getElementById('certifications-container');
    const index = certificationIndex++;
    const block = document.createElement('div');
    block.className = 'certification-block border border-slate-200 rounded-lg p-4 space-y-3';
    block.innerHTML = `
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-semibold text-slate-700">Certification ${index + 1}</h4>
            <button type="button" class="text-xs font-medium text-red-500 hover:text-red-600" onclick="removeCertification(this)">Remove</button>
        </div>
        <input type="text" name="certifications[${index}][name]" placeholder="Certification" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        <div class="grid gap-3 md:grid-cols-2">
            <input type="text" name="certifications[${index}][issuer]" placeholder="Issuer" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <input type="number" name="certifications[${index}][year]" placeholder="Year" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        </div>
    `;
    container.appendChild(block);
}

function removeCertification(button) {
    const block = button.closest('.certification-block');
    if (block) {
        block.remove();
    }
}

function addLanguage() {
    const container = document.getElementById('spoken-languages-container');
    const index = spokenLanguageIndex++;
    const row = document.createElement('div');
    row.className = 'language-row flex flex-wrap gap-3 items-center border border-slate-200 rounded-lg p-4';
    row.innerHTML = `
        <input type="text" name="spoken_languages[${index}][name]" placeholder="Language" class="flex-1 min-w-[140px] px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        <input type="text" name="spoken_languages[${index}][level]" placeholder="Level (e.g. C1, Native)" class="flex-1 min-w-[140px] px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        <button type="button" class="text-xs font-medium text-red-500 hover:text-red-600" onclick="removeLanguage(this)">Remove</button>`;
    container.appendChild(row);
}

function removeLanguage(button) {
    const row = button.closest('.language-row');
    if (row) {
        row.remove();
    }
}

function addInterest() {
    const container = document.getElementById('interests-container');
    const index = interestIndex++;
    const row = document.createElement('div');
    row.className = 'interest-row flex items-center gap-3';
    row.innerHTML = `
        <input type="text" name="interests[${index}]" placeholder="Interest or community" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        <button type="button" class="text-xs font-medium text-red-500 hover:text-red-600" onclick="removeInterest(this)">Remove</button>`;
    container.appendChild(row);
}

function removeInterest(button) {
    const row = button.closest('.interest-row');
    if (row) {
        row.remove();
    }
}

function addReference() {
    const container = document.getElementById('references-container');
    const index = referenceIndex++;
    const row = document.createElement('div');
    row.className = 'reference-row border border-slate-200 rounded-lg p-4 space-y-3';
    row.innerHTML = `
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-semibold text-slate-700">Reference ${index + 1}</h4>
            <button type="button" class="text-xs font-medium text-red-500 hover:text-red-600" onclick="removeReference(this)">Remove</button>
        </div>
        <input type="text" name="references[${index}][name]" placeholder="Name" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        <input type="text" name="references[${index}][contact]" placeholder="Contact details" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
    `;
    container.appendChild(row);
}

function removeReference(button) {
    const row = button.closest('.reference-row');
    if (row) {
        row.remove();
    }
}

function describeScore(score) {
    if (score === null || score === undefined) {
        return 'Not enough data to rate yet.';
    }

    if (score >= 85) {
        return 'Excellent ATS readiness.';
    }

    if (score >= 70) {
        return 'Strong foundation. Implement the suggestions to push higher.';
    }

    if (score >= 50) {
        return 'Needs refinement. Prioritise the recommended improvements.';
    }

    return 'Significant gaps detected. Rework each section before exporting.';
}

// ATS Analysis
async function analyzeCv() {
    const form = document.getElementById('cv-generator-form');
    const formData = new FormData(form);
    const reportContainer = document.getElementById('ats-report');
    reportContainer.innerHTML = '<p>Analyzing...</p>';
    openAtsModal();

    try {
        const response = await fetch('{{ route('profile.analyze-cv') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        });

        if (!response.ok) {
            if (response.status === 422) {
                const payload = await response.json();
                const errors = Object.values(payload.errors || {}).flat();
                throw new Error(errors.join('\n'));
            }
            throw new Error('Server error');
        }

        const result = await response.json();
        const summary = describeScore(result.atsScore);
        let reportHtml = `
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-slate-900">${result.atsScore ?? '—'}</span>
                    <span class="uppercase tracking-wide text-xs text-slate-500">/ 100 ATS</span>
                </div>
                <p class="text-sm text-slate-500 mt-1">${summary}</p>
            </div>`;

        if (Array.isArray(result.scoreBreakdown) && result.scoreBreakdown.length > 0) {
            reportHtml += '<div class="mt-4"><h4 class="font-semibold text-slate-800">Section scores</h4>';
            reportHtml += '<div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">';
            result.scoreBreakdown.forEach(row => {
                reportHtml += `
                    <div class="border border-slate-200 rounded-lg p-3">
                        <div class="flex justify-between text-sm">
                            <span class="font-semibold text-slate-800">${row.category}</span>
                            <span class="text-slate-600">${row.score}/${row.weight}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">${row.notes || ''}</p>
                    </div>`;
            });
            reportHtml += '</div></div>';
        }

        if (Array.isArray(result.highlights) && result.highlights.length > 0) {
            reportHtml += '<div class="mt-4"><h4 class="font-semibold text-emerald-700">Strengths</h4><ul class="list-disc pl-5 text-sm text-emerald-700 mt-2">';
            result.highlights.forEach(item => {
                reportHtml += `<li>${item.message ?? item}</li>`;
            });
            reportHtml += '</ul></div>';
        }

        if (Array.isArray(result.errors) && result.errors.length > 0) {
            reportHtml += '<div class="mt-4"><h4 class="font-semibold text-rose-700">Critical fixes</h4><ul class="list-disc pl-5 text-sm text-rose-700 mt-2">';
            result.errors.forEach(error => {
                reportHtml += `<li>${error.message}${error.weight ? ` (−${error.weight})` : ''}</li>`;
            });
            reportHtml += '</ul></div>';
        } else {
            reportHtml += '<p class="text-green-600 mt-4">No critical issues detected.</p>';
        }

        if (Array.isArray(result.warnings) && result.warnings.length > 0) {
            reportHtml += '<div class="mt-4"><h4 class="font-semibold text-amber-700">Recommended improvements</h4><ul class="list-disc pl-5 text-sm text-amber-700 mt-2">';
            result.warnings.forEach(warning => {
                reportHtml += `<li>${warning.message ?? warning}</li>`;
            });
            reportHtml += '</ul></div>';
        }

        reportContainer.innerHTML = reportHtml;

    } catch (error) {
        const lines = (error.message || 'Unknown error').split('\n').filter(Boolean);
        const report = lines.length > 1
            ? `<div class="text-red-600"><p class="font-semibold">Analysis failed:</p><ul class="list-disc pl-5 mt-2">${lines.map(line => `<li>${line}</li>`).join('')}</ul></div>`
            : `<p class="text-red-600">Analysis failed: ${lines[0]}</p>`;
        reportContainer.innerHTML = report;
    }
}

</script>
</main>

@include('layouts.footer')
