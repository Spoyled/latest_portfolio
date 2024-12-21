@include('layouts.header')

<!-- Main Content (this grows to fill the space) -->
<main class="flex-grow">
    <div class="w-full text-center py-32">
        <h1 class="text-2xl md:text-3xl font-bold text-center lg:text-5xl text-gray-900">
            My Posts <span class="text-black">Pro</span><span class="text-yellow-600">Snap</span>
        </h1>
    </div>

    <div class="container mx-auto px-5">
        <div class="mb-10 w-full mt-10">
            <div class="mb-16">
                <div class="w-full">
                    <div class="grid grid-cols-3 gap-10 w-full">
                        @foreach($myPosts as $post)
                            <div class="md:col-span-1 col-span-3">
                                <x-posts.post-card_dashboard :post="$post" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Footer (will always be at the bottom) -->
@include('layouts.footer')
</body>

</html>