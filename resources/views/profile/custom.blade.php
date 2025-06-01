@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl bg-white rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">
        {{ Auth::guard('employer')->check() ? 'Employer Profile' : 'User Profile' }}
    </h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 border border-green-500 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Picture and Basic Info Section -->
    <form action="{{ Auth::guard('employer')->check() ? route('employer.custom.profile.update') : route('custom.profile.update') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-6">
        @csrf
        <div class="text-center my-6">
            <div class="flex flex-col items-center">
                <!-- Profile Picture -->
                @if($user->profile_photo_path)
                    <img src="{{ asset('storage/profile_photos/' . $user->profile_photo_path) }}" 
                         class="rounded-full h-32 w-32 object-cover mb-4 shadow-lg" 
                         alt="Profile Picture">
                @else
                    <img src="{{ asset('storage/images/profile1.png') }}" 
                         class="rounded-full h-32 w-32 object-cover mb-4 shadow-lg" 
                         alt="Default Profile Picture">
                @endif
                <label for="profile_photo" class="block font-medium text-lg mt-2">Update Profile Picture</label>
                <input type="file" name="profile_photo" id="profile_photo" class="mt-2">
            </div>
        </div>

        <!-- User Details Section -->
        <div>
            <label for="name" class="block text-lg font-medium text-gray-700">
                {{ Auth::guard('employer')->check() ? 'Company Name' : 'Name' }}
            </label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" 
                   class="w-full mt-2 p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
        </div>

        @if(Auth::guard('employer')->check())
            <!-- Employer-Specific Fields -->
            <div>
                <label for="company_description" class="block text-lg font-medium text-gray-700">Company Description</label>
                <textarea name="company_description" id="company_description" rows="4" 
                          class="w-full mt-2 p-3 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Provide a brief description of your company...">{{ $user->company_description ?? '' }}</textarea>
            </div>
        @endif

        <!-- Password Section -->
        <div>
            <label for="password" class="block text-lg font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" 
                   class="w-full mt-2 p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500" 
                   placeholder="Enter New Password">
            <input type="password" name="password_confirmation" id="password_confirmation" 
                   class="w-full mt-2 p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500" 
                   placeholder="Confirm New Password">
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
                Update Profile
            </button>
        </div>
    </form>

    @if(!Auth::guard('employer')->check())
        <!-- User-Specific Sections -->
        <hr class="my-8 border-t-2 border-gray-200">

        <!-- Uploaded CV Section -->
        @if($user->cv_path)
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-700 mb-2">Uploaded CV</h2>
                <div class="bg-gray-100 p-4 rounded border border-gray-300">
                    <a href="{{ asset('storage/cvs/' . $user->cv_path) }}" target="_blank" class="text-blue-600 underline">
                        Download {{ $user->cv_path }}
                    </a>
                </div>
            </div>
        @endif

        <!-- Upload CV Section -->
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

        <!-- Generate CV Section -->
        <div class="p-6 bg-white rounded-lg shadow-md max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-4">Generate Your CV</h2>
        <form action="{{ route('profile.generate-cv') }}" method="POST" class="space-y-6">
            @csrf

            <!-- About Me Section -->
            <div>
                <label for="about_me" class="block text-lg font-medium text-gray-700">About Me</label>
                <textarea
                    name="about_me"
                    id="about_me"
                    rows="4"
                    class="w-full mt-2 p-3 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Provide a short summary about yourself..."></textarea>
            </div>

            <!-- Skills Section -->
            <div>
                <label for="skills" class="block text-lg font-medium text-gray-700">Skills</label>
                <textarea
                    name="skills"
                    id="skills"
                    rows="2"
                    class="w-full mt-2 p-3 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                    placeholder="List your skills separated by commas (e.g., Communication, Problem-solving)."></textarea>
            </div>

            <!-- Work Experience Section -->
            <div>
                <label for="work_experience" class="block text-lg font-medium text-gray-700">Work Experience</label>
                <textarea
                    name="work_experience"
                    id="work_experience"
                    rows="4"
                    class="w-full mt-2 p-3 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Describe your work experience..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button
                    type="submit"
                    class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-3 rounded-lg shadow-md font-medium hover:from-yellow-600 hover:to-yellow-700 transition duration-200">
                    Generate CV
                </button>
            </div>
        </form>
    </div>

    @else
        <!-- Job Post Statistics for Employers -->
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
        </div>
    @endif
</div>
@endsection
