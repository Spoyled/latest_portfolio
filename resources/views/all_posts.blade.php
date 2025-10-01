
@include('layouts.header')

<body class="min-h-screen flex flex-col">
    <div class="w-full text-center py-32">
        <h1 class="text-2xl md:text-3xl font-bold text-center lg:text-5xl text-gray-900">
            All Posts <span class="text-black">Pro</span><span class="text-yellow-600">Snap</span>
        </h1>
    </div>


<div class="container mx-auto px-5">
    <form action="{{ route('all_posts.index') }}" method="GET" class="mb-6 flex flex-wrap items-center justify-end space-x-4">
        @if(!auth('employer')->check())

            <div class="flex items-center space-x-2">
                <label for="salary_min" class="text-lg font-medium text-gray-700">Salary:</label>
                <input 
                    type="number" 
                    name="salary_min" 
                    id="salary_min" 
                    value="{{ $salaryMin }}" 
                    class="p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 w-24"
                    placeholder="100"
                >
            </div>
        @endif

        <div class="flex items-center space-x-2">
            <label for="name_filter" class="text-lg font-medium text-gray-700">Name:</label>
            <input 
                type="text" 
                name="name_filter" 
                id="name_filter" 
                value="{{ $nameFilter }}" 
                class="p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 w-32"
                placeholder="Name"
            >
        </div>

        <div class="flex items-center space-x-2">
            <label for="name_filter" class="text-lg font-medium text-gray-700">Location:</label>
            <input 
                type="text" 
                name="location_filter" 
                id="location_filter" 
                value="{{ $locationFilter }}" 
                class="p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 w-32"
                placeholder="USA"
            >
        </div>

        <div class="flex items-center space-x-2">
            <label for="name_filter" class="text-lg font-medium text-gray-700">Position:</label>
            <input 
                type="text" 
                name="position_filter" 
                id="position_filter" 
                value="{{ $positionFilter }}" 
                class="p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 w-32"
                placeholder="Programmer"
            >
        </div>

        <button 
            type="submit" 
            class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition"
        >
            Apply
        </button>
    </form>
</div>

    </div>

    <!-- Main Content Section -->
    <main class="container mx-auto px-5 flex-grow">
        <div class="mb-10 w-full mt-10">
            <div class="mb-16">
                <div class="w-full">
                    <div class="grid grid-cols-3 gap-10 w-full">
                        @forelse($allPosts as $post)
                            <div class="md:col-span-1 col-span-3">
                                <x-posts.post-card_dashboard :post="$post" />
                            </div>
                        @empty
                            <p class="col-span-3 text-center text-gray-600">No posts available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('layouts.footer')
</body>
