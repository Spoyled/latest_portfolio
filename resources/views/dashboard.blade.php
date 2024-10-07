<!-- Header -->
    @include('layouts.header')
        

    <div class="w-full text-center py-32">
        <h1 class="text-2xl md:text-3xl font-bold text-center lg:text-5xl text-gray-700">
            Dashboard <span class="text-black">Pro</span><span class="text-yellow-500">Snap</span>
        </h1>
    </div>

    <main class="container mx-auto px-5 flex flex-grow">
        <div class="mb-10 w-full">
            <div class="mb-16">
                <h2 class="mt-16 mb-5 text-3xl text-yellow-500 font-bold">Featured Posts</h2>
                <div class="w-full">
                    <div class="grid grid-cols-3 gap-10 w-full">

                        @foreach($featuredPosts as $post)
                        <div class = "md:col-span-1 col-span-3">
                            <x-posts.post-card_dashboard :post="$post"/>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr>

            <h2 class="mt-16 mb-5 text-3xl text-yellow-500 font-bold">Latest Posts</h2>
            <div class="w-full mb-5">
                <div class="grid grid-cols-3 gap-10 gap-y-32 w-full">
                @foreach($latestPosts as $post)
                        <div class = "md:col-span-1 col-span-3">
                            <x-posts.post-card_dashboard :post="$post"/>
                        </div>
                @endforeach
                </div>
            </div>
            <a class="mt-10 block text-center text-lg text-yellow-500 font-semibold"
                href="{{ url('/all_posts') }}">More
                Posts</a>
        </div>
    </main>
    

    
<!-- Footer -->
@include('layouts.footer')

