@props(['post'])

@php
    $route = route('posts.show', $post->id);
    $when = $post->published_at ?? $post->created_at ?? null;
    $whenStr = $when instanceof \Carbon\Carbon
        ? $when->format('Y-m-d H:i')
        : (is_string($when) ? \Carbon\Carbon::parse($when)->format('Y-m-d H:i') : '');

    $imageUrl = !empty($post->image)
        ? asset('storage/posts/' . $post->image)
        : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png';
@endphp

<div>
    <a href="{{ $route }}">
        <img class="object-cover h-96 w-96 rounded-full shadow-lg"
             src="{{ $imageUrl }}" alt="Post image">
    </a>

    <div class="mt-3">
        <div class="flex items-center mb-2">
            <p class="text-gray-500 text-sm">{{ $whenStr }}</p>
        </div>
        <a href="{{ $route }}" class="text-xl font-bold text-gray-900">
            {{ $post->title }}
        </a>
    </div>
</div>
