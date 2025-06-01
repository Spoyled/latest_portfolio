<!-- Header -->
@include('layouts.header')

<body class="min-h-screen flex flex-col">
    <div class="w-full text-center py-32">
        <h1 class="text-2xl md:text-3xl font-bold text-center lg:text-5xl text-gray-900">
            All Posts <span class="text-black">Pro</span><span class="text-yellow-600">Snap</span>
        </h1>
    </div>

<!-- Filter Section -->
<div class="container mx-auto px-5">
    <form action="" method="GET" class="mb-6 flex flex-wrap items-center justify-end space-x-4">
        @if(!auth('employer')->check())
            <!-- Salary Filter (Visible for Non-Employers Only) -->
            <div class="flex items-center space-x-2">
                <label for="salary_min" class="text-lg font-medium text-gray-700">Salary:</label>
                <input 
                    type="number" 
                    name="salary_min" 
                    id="salary_min" 
                    value="{{ $salaryMin }}" 
                    class="p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 w-24"
                    placeholder="e.g., 1000"
                >
            </div>
        @endif

        <!-- Name Filter -->
        <div class="flex items-center space-x-2">
            <label for="name_filter" class="text-lg font-medium text-gray-700">Name:</label>
            <input 
                type="text" 
                name="name_filter" 
                id="name_filter" 
                value="{{ $nameFilter }}" 
                class="p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 w-32"
                placeholder="e.g., IT"
            >
        </div>

        <!-- Skills Filter -->
        <div class="flex items-center space-x-2">
            <label for="skills_filter" class="text-lg font-medium text-gray-700">Skills:</label>
            <input 
                type="text" 
                name="skills_filter" 
                id="skills_filter" 
                value="{{ $skillsFilter }}" 
                class="p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 w-32"
                placeholder="e.g., Programmer"
            >
        </div>

        <!-- Sort Options -->
        <div class="flex items-center space-x-2">
            <label for="sort" class="text-lg font-medium text-gray-700">Sort:</label>
            <select 
                name="sort" 
                id="sort" 
                class="p-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
            >
                @if(!auth('employer')->check())
                    <option value="salary" {{ $sortOption === 'salary' ? 'selected' : '' }}>Salary</option>
                @endif
                <option value="default" {{ $sortOption === 'default' ? 'selected' : '' }}>Default</option>
                <option value="name" {{ $sortOption === 'name' ? 'selected' : '' }}>Name</option>
                <option value="skills" {{ $sortOption === 'skills' ? 'selected' : '' }}>Skills</option>
            </select>
        </div>

        <!-- Submit Button -->
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
