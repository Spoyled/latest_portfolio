@props(['post'])
<div class="">
    <a href="{{ route('posts.show', $post) }}">
        <div>
        <img class="object-cover h-96 w-96 rounded-full shadow-lg" src="https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png">
        </div>
    </a>
    <div class="mt-3">
        <div class="flex items-center mb-2">
            <a href="{{ route('posts.show', $post) }}">
            <p class="text-gray-500 text-sm">{{$post->published_at }}</p>
        </div>
        <a href ="{{ route('posts.show', $post) }}" class="text-xl font-bold text-gray-900">{{ $post->title }}</a>
    </div>

</div>