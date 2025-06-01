@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-3xl p-6 bg-white rounded shadow">
    <h1 class="text-3xl font-bold mb-6 text-yellow-600">Edit Resume Post</h1>

    <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="body" rows="4" class="w-full border p-2 rounded" required>{{ old('body', $post->body) }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Education</label>
            <input type="text" name="education" pattern="[A-Za-z\s,]+" title="Only letters, spaces, and commas"
                   value="{{ old('education', $post->education) }}" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block font-semibold mb-1">Skills</label>
            <input type="text" name="skills" pattern="[A-Za-z\s,]+" title="Only letters, spaces, and commas"
                   value="{{ old('skills', $post->skills) }}" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Additional Links (optional)</label>
            <input type="url" name="additional_links" value="{{ old('additional_links', $post->additional_links) }}" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block font-semibold mb-1">Update Image</label>
            <input type="file" name="image" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block font-semibold mb-1">Update Resume (PDF, DOC, DOCX)</label>
            <input type="file" name="resume" class="w-full border p-2 rounded" accept=".pdf,.doc,.docx">
        </div>

        <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded hover:bg-yellow-700 transition">
            Update Resume
        </button>
    </form>
</div>
@endsection
