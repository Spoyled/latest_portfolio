<!-- Header -->
@include('layouts.header')

<div class="relative w-full" style="max-width: 100%; height: 400px;">
    <!-- Image -->
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}" alt="ProSnap Image"
        class="w-full h-full object-cover">

    <!-- Overlay content -->
    <div class="absolute inset-0 flex flex-col items-center justify-center bg-black bg-opacity-50 text-white p-6">
        <h1 class="text-3xl md:text-4xl font-bold text-yellow-500">
    </div>

    <!-- Extended Shadow beneath the image -->
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-b from-transparent to-[#f0f4f8]"></div>
</div>


<main class="container mx-auto px-5 flex flex-grow">
    <div class="mb-10 w-full">
        <div class="mb-16">
        <h2 class="mt-16 mb-5 text-3xl text-yellow-600 font-bold">Featured Posts</h2>
        <div class="w-full">
            @if($featuredPosts->isEmpty())
                <p class="text-center text-gray-600 text-lg">No featured posts available at the moment.</p>
            @else
                <div class="grid grid-cols-3 gap-10 w-full">
                    @foreach($featuredPosts as $post)
                        <div class="md:col-span-1 col-span-3">
                            <x-posts.post-card_dashboard :post="$post" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="w-full my-8">
            <div class="h-1 mx-auto bg-gradient-to-r from-yellow-500 via-yellow-400 to-yellow-600 rounded-full"
                style="width: 80%;"></div>
        </div>

        <h2 class="mt-16 mb-5 text-3xl text-yellow-600 font-bold">Latest Posts</h2>
        <div class="w-full mb-5">
            @if($latestPosts->isEmpty())
                <p class="text-center text-gray-600 text-lg">No latest posts found.</p>
            @else
                <div class="grid grid-cols-3 gap-10 gap-y-32 w-full">
                    @foreach($latestPosts as $post)
                        <div class="md:col-span-1 col-span-3">
                            <x-posts.post-card_dashboard :post="$post" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <a class="mt-10 block text-center text-lg text-yellow-600 font-semibold" href="{{ url('/AllPosts') }}">
            More Posts
        </a>

    </div>
</main>

<!-- Footer -->
@include('layouts.footer')

