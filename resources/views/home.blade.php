<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="font-light antialiased bg-[#f0f4f8]">
    <header class="flex items-center justify-between py-2 px-4 bg-blue-900 border-b border-yellow-500">

        <div id="header-left" class="flex items-center">
            <div class="text-gray-800 font-semibold">
                <a href="" class="text-2xl font-semibold text-white hover:text-yellow-500">
                    Pro<span class="text-yellow-400">Snap</span>
                </a>
            </div>
            <div class="top-menu ml-10">
                <ul class="flex space-x-6">
                    <li>
                        <a class="hover:text-yellow-400 text-sm text-white"
                            href="http://127.0.0.1:8000">
                            Home
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-yellow-400 text-sm text-white"
                            href="http://127.0.0.1:8000/MyPosts">
                            My Posts
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-yellow-400 text-sm text-white"
                             href="http://127.0.0.1:8000/AllPosts">All Posts</a>
                    </li>
                    <li>
                        <a class="hover:text-yellow-400 text-sm text-white"
                            href="http://127.0.0.1:8000/Create">
                            Make a Post
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="header-right" class="flex items-center md:space-x-6">
            <div class="flex space-x-5">
                <a class="hover:text-yellow-400 text-sm text-white"
                    href="http://127.0.0.1:8000/login">
                    Login
                </a>
                <a class="hover:text-yellow-400 text-sm text-white"
                    href="http://127.0.0.1:8000/register">
                    Register
                </a>
            </div>
        </div>
    </header>



    <div class="relative w-full" style="max-width: 100%; height: 400px;">
        <!-- Image -->
        <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}" alt="ProSnap Image" class="w-full h-full object-cover">

        <!-- Overlay content -->
        <div class="absolute inset-0 flex flex-col items-center justify-center bg-black bg-opacity-50 text-white p-6">
            <h1 class="text-3xl md:text-4xl font-bold text-yellow-500">Welcome to ProSnap</h1>
            <p class="text-lg md:text-xl mt-4">Find it difficult to find a job? Post your resume NOW and get a job in less than 24 hours!!!</p>
            <a href="#" class="mt-6 bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded-lg">Post your Resume</a>
        </div>

        <!-- Extended Shadow beneath the image -->
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-b from-transparent to-[#f0f4f8]"></div>
    </div>




    <main class="container mx-auto px-5 flex flex-grow">
        <div class="mb-10 w-full">
            <div class="mb-16">
                <h2 class="mt-16 mb-5 text-3xl text-yellow-600 font-bold">Featured Posts</h2>
                <div class="w-full">
                    <div class="grid grid-cols-3 gap-10 w-full">

                        @foreach($featuredPosts as $post)
                        <div class = "md:col-span-1 col-span-3">
                            <x-posts.post-card :post="$post"/>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="w-full my-8">
                <div class="h-1 mx-auto bg-gradient-to-r from-yellow-500 via-yellow-400 to-yellow-600 rounded-full" style="width: 80%;"></div>
            </div>

            <h2 class="mt-16 mb-5 text-3xl text-yellow-600 font-bold">Latest Posts</h2>
            <div class="w-full mb-5">
                <div class="grid grid-cols-3 gap-10 gap-y-32 w-full">
                @foreach($latestPosts as $post)
                        <div class = "md:col-span-1 col-span-3">
                            <x-posts.post-card :post="$post"/>
                        </div>
                @endforeach
                </div>
            </div>
            <a class="mt-10 block text-center text-lg text-yellow-600 font-semibold"
                href="{{ url('/AllPosts') }}">More
                Posts</a>
        </div>
    </main>

    <footer class="text-base space-x-6 flex items-center justify-center py-4 bg-blue-900 border-t border-yellow-500 text-white">
        <a class="hover:text-yellow-400" href="{{ url('/login') }}">Login</a>
        <a class="hover:text-yellow-400" href="{{ url('/register') }}">Register</a>
        <a class="hover:text-yellow-400 text-sm text-white"
           href="http://127.0.0.1:8000/EmployerRegister">
           Employer Register
        </a>
    </footer>
</body>