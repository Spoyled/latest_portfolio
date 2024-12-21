<!-- Header -->
@include('layouts.header')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="w-full text-center py-32">
    <h1 class="text-2xl md:text-3xl font-bold text-center lg:text-5xl text-yellow-900">
        {{ $post->name }}'s <span class="text-black">post - </span><span
            class="text-yellow-600">{{ $post->title }}</span>
    </h1>
</div>



<main class="container mx-auto px-5 flex flex-grow">
    <div class="w-full mb-10">
        <div class="mb-16">
            <div class="flex justify-center items-center">
                <img class="object-cover h-96 w-96 rounded-full shadow-lg"
                    src="{{ asset('storage/posts/' . $post->image) }}" alt="Post Image">
            </div>

            <h3 class="text-3xl font-bold text-center text-gray-900 mb-4">Resume Details</h3>

            <div class="text-left mt-6 px-4">
                <p class="text-xl font-semibold text-gray-900 mb-2">Description</p>
                <p class="text-base text-gray-600">{{ $post->body }}</p>

                <p class="text-xl font-semibold text-gray-900 mt-4 mb-2">Education</p>
                <p class="text-base text-gray-600">{{ $post->education }}</p>

                <p class="text-xl font-semibold text-gray-900 mt-4 mb-2">Skills</p>
                <p class="text-base text-gray-600">{{ $post->skills }}</p>

                <div class="flex flex-col space-y-2 mt-4">
                    <a href="{{ $post->additional_links }}"
                        class="bg-blue-500 text-white font-bold py-2 px-4 w-40 rounded hover:bg-blue-700 transition-colors duration-300 ease-in-out"
                        aria-label="Visit additional links">
                        Additional Link
                    </a>
                    <a href="{{ asset('storage/resumes/' . $post->resume) }}"
                        class="bg-blue-500 text-white font-bold py-2 px-4 w-40 rounded hover:bg-blue-700 transition-colors duration-300 ease-in-out"
                        aria-label="Download resume PDF">
                        Download PDF
                    </a>
                </div>
            </div>

        </div>
    </div>
</main>

<div class="bg-white py-8 px-6 shadow rounded-lg my-6">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-900">Comments</h2>
        <div class="border-b-2 border-gray-200 my-2"></div>
    </div>

    <div class="space-y-4">
        @foreach ($post->comments as $comment)
            <div class="comment p-4 bg-gray-100 rounded-lg" id="comment-{{ $comment->id }}">
                <p class="font-semibold text-gray-900">{{ $comment->user ? $comment->user->name : 'Unknown user' }} says:
                </p>
                <p class="text-gray-600">{{ $comment->body }}</p>

                @if(Auth::check() && Auth::user()->is_admin) <!-- Check if user is admin -->
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                        class="inline-block delete-comment-form" data-comment-id="{{ $comment->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            class="text-red-500 font-bold hover:text-red-700 transition-colors delete-comment-btn">Delete</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>

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
                success: function (response) {
                    $(`#comment-${commentId}`).remove();
                },
                error: function () {
                    alert('Error deleting comment');
                }
            });
        });

    </script>


    <div class="mt-6">
        <form action="{{ route('post.comments.store', $post->id) }}" method="POST" class="space-y-4">
            @csrf
            <textarea name="body"
                class="w-full p-3 rounded border border-gray-300 focus:outline-none focus:border-blue-500 transition-colors"
                placeholder="Add a comment..." required></textarea>
            <button type="submit"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition-colors duration-300 ease-in-out">Post
                Comment</button>
        </form>
    </div>

</div>
<!-- Footer -->
@include('layouts.footer')
