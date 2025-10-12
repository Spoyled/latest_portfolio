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
                                <p class="text-4xl font-bold text-blue-900 mt-2">{{ $totalJobPosts }}</p>
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
                                <p class="text-4xl font-bold text-green-900 mt-2">{{ $activeJobPosts }}</p>
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
                                <p class="text-4xl font-bold text-purple-900 mt-2">{{ $applicationsReceived }}</p>
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
                                <p class="text-4xl font-bold text-gray-900 mt-2">{{ $closedJobPosts }}</p>
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


        <!-- User-Specific Sections -->
        <hr class="my-8 border-t-2 border-gray-200">

        <!-- Uploaded CV Section -->
        @if($user->cv_path)
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-700 mb-2">Uploaded CV</h2>
                <div class="bg-gray-100 p-4 rounded border border-gray-300">
                    <a href="{{ asset('storage/' . $user->cv_path) }}" target="_blank" class="text-blue-600 underline">
                        Download {{ basename($user->cv_path) }}
                    </a>
                </div>
            </div>
        @endif


      
        <form action="{{ route('profile.upload-cv') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label for="cv_file" class="block text-lg font-medium text-gray-700">Upload CV</label>
                <input type="file" name="cv_file" id="cv_file" class="w-full mt-2 p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="text-center">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">
                    Upload CV
                </button>
            </div>
        </form>

        <hr class="my-8 border-t-2 border-gray-200">

        
        <div class="p-6 bg-white rounded-lg shadow-md max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-4">Generate Your CV</h2>
        <form action="{{ route('profile.generate-cv') }}" method="POST" class="space-y-6">
            @csrf

            
            <div>
                <label for="about_me" class="block text-lg font-medium text-gray-700">About Me</label>
                <textarea
                    name="about_me"
                    id="about_me"
                    rows="4"
                    required
                    class="w-full mt-2 p-3 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Provide a short summary about yourself..."></textarea>
            </div>

          
            <div>
                <label for="skills" class="block text-lg font-medium text-gray-700">Skills</label>
                <textarea
                    name="skills"
                    id="skills"
                    rows="2"
                    required
                    class="w-full mt-2 p-3 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                    placeholder="List your skills separated by commas (e.g., Communication, Problem-solving)."></textarea>
            </div>

            <div id="experience-container">
                <h3 class="text-lg font-semibold text-gray-700">Work Experience</h3>

                <div class="experience-group mb-4">
                    <label class="block mt-2 text-gray-700">Job Title</label>
                    <input type="text" name="job_title[]" maxlength="255" class="w-full p-2 border rounded" value="{{ old('job_title.0') }}">

                    <label class="block mt-2 text-gray-700">Company</label>
                    <input type="text" name="company[]" maxlength="255" class="w-full p-2 border rounded" value="{{ old('company.0') }}">

                    <label class="block mt-2 text-gray-700">Duration</label>
                    <input type="text" name="duration[]" maxlength="255" class="w-full p-2 border rounded" value="{{ old('duration.0') }}">

                    <label class="block mt-2 text-gray-700">Description</label>
                    <textarea name="job_description[]" rows="3" maxlength="1000" class="w-full p-2 border rounded">{{ old('job_description.0') }}</textarea>
                </div>
            </div>

            <button type="button" onclick="addExperience()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">
                Add Another Experience
            </button>



            <div>
                <label for="education" class="block text-lg font-medium text-gray-700">Education</label>
                <textarea name="education" id="education" rows="3" maxlength="500"
                required
                    class="w-full mt-2 p-3 border border-gray-300 rounded"
                    placeholder="Your education history...">{{ old('education') }}</textarea>
            </div>

            <div class="text-center">
                <button type="submit"
                    class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-3 rounded-lg shadow-md font-medium hover:from-yellow-600 hover:to-yellow-700 transition duration-200">
                    Generate CV
                </button>
            </div>

        </form>
    </div> 
    

    {{-- @else
        Job Post Statistics for Employers 
        <hr class="my-8 border-t-2 border-gray-200">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Job Post Statistics</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-blue-100 p-4 rounded shadow">
                    <h3 class="text-lg font-bold text-blue-800">Total Job Posts</h3>
                    <p class="text-2xl font-bold text-center text-blue-900">{{ $totalJobPosts }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded shadow">
                    <h3 class="text-lg font-bold text-green-800">Active Job Posts</h3>
                    <p class="text-2xl font-bold text-center text-green-900">{{ $activeJobPosts }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded shadow">
                    <h3 class="text-lg font-bold text-yellow-800">Applications Received</h3>
                    <p class="text-2xl font-bold text-center text-yellow-900">{{ $applicationsReceived }}</p>
                </div>
            </div>
        </div>--}}
    @endif 
</div>
@endsection

<script>
function addExperience() {
    const container = document.getElementById('experience-container');
    const group = document.querySelector('.experience-group');
    const clone = group.cloneNode(true);

    // Clear inputs in the cloned block
    clone.querySelectorAll('input, textarea').forEach(el => el.value = '');

    container.appendChild(clone);
}
</script>
