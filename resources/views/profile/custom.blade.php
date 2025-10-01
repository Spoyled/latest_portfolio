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

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <strong>Whoops!</strong> There were some problems with your input:
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

    @if(Auth::guard('employer')->check())
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
                <div class="bg-red-100 p-4 rounded shadow">
                    <h3 class="text-lg font-bold text-red-800">Closed Job Posts</h3>
                    <p class="text-2xl font-bold text-center text-red-900">{{ $closedJobPosts }}</p>
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
