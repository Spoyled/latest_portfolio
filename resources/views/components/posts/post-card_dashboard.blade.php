@props(['post'])
<div class="">
    @php
        $route = auth('employer')->check() 
            ? route('employer.posts.show', $post) 
            : route('posts.show', $post);
    @endphp

    <a href="{{ $route }}">
        <div>
            <img class="object-cover h-96 w-96 rounded-full shadow-lg" src="{{ asset('storage/posts/' . $post->image) }}" alt="Post Image">
        </div>
    </a>
    <div class="mt-3">
        <div class="flex items-center mb-2">
            <p class="text-gray-500 text-sm">Created at {{ $post->published_at }}</p>
        </div>
        <a href="{{ $route }}" class="text-xl font-bold text-gray-900">{{ $post->title }}</a>
    </div>
</div>
