{{-- resources/views/employer/posts.blade.php --}}
@include('layouts.header')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="w-full text-center py-32">
  <h1 class="text-2xl md:text-3xl font-bold text-center lg:text-5xl text-yellow-900">
    Job Position: <span class="text-yellow-600">{{ $post->title }}</span>
  </h1>
</div>

<main class="container mx-auto px-5 flex flex-grow">
  <div class="w-full mb-10">
    {{-- Job Image --}}
    <div class="flex justify-center items-center mb-8">
      <img
        class="object-cover h-96 w-96 rounded-lg shadow-lg"
        src="{{ !empty($post->image) ? asset('storage/posts/'.$post->image) : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png' }}"
        alt="Job Image">
    </div>

    {{-- Job Details + Applicants --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6 px-4">
      {{-- Details --}}
      <div>
        <h2 class="text-3xl font-bold text-yellow-600 mb-4">Job Details</h2>

        <p class="text-xl font-semibold text-gray-900 mb-2">Description</p>
        <p class="text-gray-700 mb-4">{{ $post->body }}</p>

        <p class="text-xl font-semibold text-gray-900 mt-4 mb-2">Location</p>
        <p class="text-gray-700 mb-4">{{ $post->location ?? '—' }}</p>

        <p class="text-xl font-semibold text-gray-900 mt-4 mb-2">Position</p>
        <p class="text-gray-700 mb-4">{{ $post->position ?? '—' }}</p>

        <p class="text-xl font-semibold text-gray-900 mt-4 mb-2">Required Skills</p>
        <p class="text-gray-700 mb-4">{{ $post->skills ?? '—' }}</p>

        <p class="text-xl font-semibold text-gray-900 mt-4 mb-2">Salary</p>
        <p class="text-gray-700 mb-4">
          @if(!is_null($post->salary) && $post->salary !== '')
            ${{ number_format((float)$post->salary, 2) }}
          @else
            —
          @endif
        </p>

        @if(!empty($post->additional_links))
          <div class="mt-6">
            <a href="{{ $post->additional_links }}"
               class="inline-block bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition-colors">
              Additional Link
            </a>
          </div>
        @endif
      </div>

      {{-- Applicants / Actions --}}
      <div class="mt-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Applicants</h2>
        <p class="text-gray-700 text-lg mb-4">
          Total Applicants:
          <span class="font-bold text-yellow-600">{{ $applicantsCount }}</span>
        </p>

        @if(auth('employer')->check() && auth('employer')->id() === ($post->employer_id ?? null))
          <div class="space-x-2">
            <a href="{{ route('posts.applicants', $post->id) }}"
               class="inline-block bg-blue-500 text-white py-2 px-6 rounded-lg font-bold hover:bg-blue-600 transition">
              View Applicants
            </a>

            <a href="{{ route('employer.posts.edit', $post->id) }}"
               class="inline-block bg-yellow-500 text-white py-2 px-4 rounded-lg font-bold hover:bg-yellow-600 transition">
              Edit Post
            </a>
          </div>

         @php
           $isClosed = !empty($post->closed_at) || (isset($post->is_active) && (int)$post->is_active === 0);
         @endphp



          <div class="mt-3">
            @if(!$isClosed)
              <form method="POST" action="{{ route('posts.close', $post->id) }}">
                @csrf
                <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                  Close Post
                </button>
              </form>
            @else
              <p class="text-red-600 font-semibold">
                @if(!empty($post->closed_at))
                  This post was closed on {{ \Carbon\Carbon::parse($post->closed_at)->format('Y-m-d') }}
                @else
                  This post is closed.
                @endif
              </p>
            @endif

          </div>
        @endif
      </div>
    </div>

    {{-- Apply (non-employer authenticated users) --}}
    @if(auth()->check() && !auth('employer')->check())
      <div class="mt-8 text-center">
        @if($hasApplied)
          <p class="text-green-600 font-semibold">
            Thank you for applying! The hiring team will contact you shortly.
          </p>
        @elseif(!empty($post->closed_at))
          <p class="text-red-600 font-semibold">This job offer is closed.</p>
        @else
          <button id="applyButton"
                  class="bg-green-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-green-600 transition"
                  onclick="openApplyModal()">
            Apply
          </button>
        @endif
      </div>

      {{-- Apply Modal --}}
      <div id="applyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
          <h2 class="text-lg font-bold mb-4">Upload Your CV</h2>

          <form action="{{ route('posts.apply', $postModel->id ?? $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
              <label for="cv_file" class="block text-gray-700 font-medium">Select CV File</label>
              <input type="file" name="cv_file" id="cv_file" class="block w-full mt-2 p-2 border rounded"
                    accept=".pdf,.doc,.docx">
              <p class="text-sm text-gray-600 mt-1">
                Optional: if you’ve already uploaded/generated a CV, you can apply without uploading again.
              </p>

              @if ($errors->has('cv_file'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->first('cv_file') }}</p>
                <script>
                  document.addEventListener('DOMContentLoaded', () => openApplyModal());
                </script>
              @endif
            </div>

            <div class="flex justify-between items-center">
              <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                Submit Application
              </button>
              <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition"
                      onclick="closeApplyModal()">
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    @endif
</main>

@if (session('success'))
  <div class="mx-auto max-w-3xl my-4 bg-green-50 text-green-800 px-4 py-3 rounded">
    {{ session('success') }}
  </div>
@endif
@if ($errors->any())
  <div class="mx-auto max-w-3xl my-4 bg-red-50 text-red-800 px-4 py-3 rounded">
    {{ $errors->first() }}
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

{{-- Comments --}}
<div class="bg-white py-8 px-6 shadow rounded-lg my-6 container mx-auto">
  <div class="mb-4">
    <h2 class="text-2xl font-bold text-gray-900">Information</h2>
    <div class="border-b-2 border-gray-200 my-2"></div>
  </div>

  <div class="space-y-4">
    @foreach ($post->comments as $comment)
      <div class="comment p-4 bg-gray-100 rounded-lg" id="comment-{{ $comment->id }}">
        <p class="font-semibold text-gray-900">
          @if ($comment->user)
            {{ $comment->user->name }}
          @elseif (property_exists($comment, 'employer') && $comment->employer)
            {{ $comment->employer->name }}
          @else
            Unknown User
          @endif
          says:
        </p>
        <p class="text-gray-600">{{ $comment->body }}</p>

        @if(Auth::check() && Auth::user()->is_admin)
          <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                class="inline-block delete-comment-form" data-comment-id="{{ $comment->id }}">
            @csrf @method('DELETE')
            <button type="button"
                    class="text-red-500 font-bold hover:text-red-700 transition-colors delete-comment-btn">
              Delete
            </button>
          </form>
        @endif
      </div>
    @endforeach
  </div>

  @if(Auth::check() || auth('employer')->check())
    <div class="mt-6">
      <form action="{{ route('post.comments.store', $post->id) }}" method="POST" class="space-y-4">
        @csrf
        <textarea name="body" class="w-full p-3 rounded border border-gray-300 focus:outline-none focus:border-blue-500"
                  placeholder="Add a comment..." required></textarea>
        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
          Post Comment
        </button>
      </form>
    </div>
  @else
    <p class="text-gray-600 mt-6">Only authenticated users can comment.</p>
  @endif
</div>

@if (($openApply ?? false) && auth()->check() && !auth('employer')->check())
<script>
  document.addEventListener('DOMContentLoaded', () => openApplyModal());
</script>
@endif



<script>
  $(document).on('click', '.delete-comment-btn', function () {
    const form = $(this).closest('form');
    const id = form.data('comment-id');
    $.post(form.attr('action'), {_method:'DELETE', _token:'{{ csrf_token() }}'})
     .done(() => $('#comment-'+id).remove())
     .fail(() => alert('Error deleting comment'));
  });
</script>

@include('layouts.footer')
