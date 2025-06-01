<!-- Header -->
@include('layouts.header')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="w-full text-center py-32">
    <h1 class="text-2xl md:text-3xl font-bold text-center lg:text-5xl text-yellow-900">
        Job Position: <span class="text-yellow-600">{{ $post->title }}</span>
    </h1>
</div>

<main class="container mx-auto px-5 flex flex-grow">
    <div class="w-full mb-10">
        <!-- Job Image -->
        <div class="flex justify-center items-center mb-8">
            <img class="object-cover h-96 w-96 rounded-lg shadow-lg" src="{{ asset('storage/posts/' . $post->image) }}"
                alt="Job Image">
        </div>

        <!-- Job Details and Applicants -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6 px-4">
            <!-- Job Offer Details -->
            <div>
                <h2 class="text-3xl font-bold text-yellow-600 mb-4">Job Details</h2>

                <p class="text-xl font-semibold text-gray-900 mb-2">Description</p>
                <p class="text-gray-700 mb-4">{{ $post->body }}</p>

                <p class="text-xl font-semibold text-gray-900 mt-4 mb-2">Location</p>
                <p class="text-gray-700 mb-4">{{ $post->location }}</p>

                <p class="text-xl font-semibold text-gray-900 mt-4 mb-2">Position</p>
                <p class="text-gray-700 mb-4">{{ $post->position }}</p>

                <p class="text-xl font-semibold text-gray-900 mt-4 mb-2">Required Skills</p>
                <p class="text-gray-700 mb-4">{{ $post->skills }}</p>

                <p class="text-xl font-semibold text-gray-900 mt-4 mb-2">Salary</p>
                <p class="text-gray-700 mb-4">${{ number_format($post->salary, 2) }}</p>

                <div class="flex flex-col space-y-2 mt-6">
                    @if($post->additional_links)
                        <a href="{{ $post->additional_links }}"
                            class="bg-blue-500 text-white font-bold py-2 px-4 w-40 rounded hover:bg-blue-700 transition-colors duration-300">
                            Additional Link
                        </a>
                    @endif
                </div>
            </div>

            <!-- Applicants Section -->
             <div class="mt-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Applicants</h2>
                <p class="text-gray-700 text-lg mb-4">
                    Total Applicants: <span class="font-bold text-yellow-600">{{ $post->applicants()->count() }}</span>
                </p>

                
                @if(auth('employer')->check() && auth('employer')->id() === $post->employer_id)
                    <a href="{{ route('posts.applicants', $post->id) }}" 
                        class="inline-block bg-blue-500 text-white py-2 px-6 rounded-lg font-bold hover:bg-blue-600 transition">
                        View Applicants
                    </a>
                @endif

                @if(auth('employer')->check() && auth('employer')->id() === $post->employer_id)
                <a href="{{ route('employer.posts.edit', $post->id) }}" 
                    class="inline-block bg-yellow-500 text-white py-2 px-4 rounded-lg font-bold hover:bg-yellow-600 transition">
                    Edit Post
                </a>    

                    <form method="POST" action="{{ route('posts.close', $post->id) }}">
                        @csrf
                        <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                            Close Post
                        </button>
                    </form>
                @elseif($post->closed_at)
                    <p class="text-red-600 font-semibold mt-4">
                        This post was closed on {{ \Carbon\Carbon::parse($post->closed_at)->format('Y-m-d') }}
                    </p>
                @endif
            </div> 
        </div>
    </div>
</main>
    
@if(auth()->check() && !auth('employer')->check())
        <div class="mt-6 text-center">
            @if($post->applicants()->where('user_id', auth()->id())->exists())
                <p class="text-green-600 font-semibold">
                    Thank you for applying! The hiring team will contact you shortly.
                </p>
            @else
                <button 
                    id="applyButton" 
                    class="bg-green-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-green-600 transition"
                    onclick="openApplyModal()"
                >
                    Apply
                </button>
            @endif
        </div>

    <div id="applyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-lg font-bold mb-4">Upload Your CV</h2>
            <form action="{{ route('posts.apply', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="cv_file" class="block text-gray-700 font-medium">Select CV File</label>
                    <input 
                        type="file" 
                        name="cv_file" 
                        id="cv_file" 
                        class="block w-full mt-2 p-2 border rounded"
                        accept=".pdf,.doc,.docx"
                    >
                    <p class="text-sm text-gray-600 mt-1">
                        (Optional: If you’ve already uploaded or generated a CV, you can apply without uploading again.)
                    </p>

                    {{-- ✅ Display validation error --}}
                    @if ($errors->has('cv_file'))
                        <p class="text-red-600 text-sm mt-1">{{ $errors->first('cv_file') }}</p>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                openApplyModal();
                            });
                        </script>
                    @endif
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                        Submit Application
                    </button>
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition" onclick="closeApplyModal()">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

@endif

<script>
    function openApplyModal() {
        document.getElementById('applyModal').classList.remove('hidden');
    }

    function closeApplyModal() {
        document.getElementById('applyModal').classList.add('hidden');
    }
</script>



<!-- Comments Section -->
<div class="bg-white py-8 px-6 shadow rounded-lg my-6 container mx-auto">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-900">Information</h2>
        <div class="border-b-2 border-gray-200 my-2"></div>
    </div>

    <!-- Display Existing Comments -->
    <div class="space-y-4">
        @foreach ($post->comments as $comment)
            <div class="comment p-4 bg-gray-100 rounded-lg" id="comment-{{ $comment->id }}">
            <p class="font-semibold text-gray-900">
                @if ($comment->user)
                    {{ $comment->user->name }}
                @elseif ($comment->employer)
                    {{ $comment->employer->name }}
                @else
                    Unknown User
                @endif
                says:
            </p>

                <p class="text-gray-600">{{ $comment->body }}</p>

                @if(Auth::check() && Auth::user()->is_admin)
                    <!-- Allow admins to delete comments -->
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline-block delete-comment-form" data-comment-id="{{ $comment->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="text-red-500 font-bold hover:text-red-700 transition-colors delete-comment-btn">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
    

    <!-- Add Comment Form -->
    @if(Auth::check() && (Auth::id() === $post->user_id || auth('employer')->check()))
        <!-- Allow employers or post owners to comment -->
        <div class="mt-6">
            <form action="{{ route('post.comments.store', $post->id) }}" method="POST" class="space-y-4">
                @csrf
                <textarea name="body" class="w-full p-3 rounded border border-gray-300 focus:outline-none focus:border-blue-500 transition-colors" placeholder="Add a comment..." required></textarea>
                <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition-colors duration-300">
                    Post Comment
                </button>
            </form>
        </div>
    @else
        <p class="text-gray-600 mt-6">
            Only the post owner or authenticated users can comment.
        </p>
    @endif
</div>



<!-- AJAX Delete Comment Script -->
<script>
    $(document).on('click', '.delete-comment-btn', function () {
        const form = $(this).closest('form');
        const commentId = form.data('comment-id');
        const actionUrl = form.attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                $(`#comment-${commentId}`).remove();
            },
            error: function () {
                alert('Error deleting comment');
            }
        });
    });
</script>

<!-- Footer -->
@include('layouts.footer')
