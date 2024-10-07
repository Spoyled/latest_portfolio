<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-light antialiased">
    <header class="flex items-center justify-between py-3 px-6 border-b border-gray-100">
        <div id="header-left" class="flex items-center">
        <div class="text-gray-800 font-semibold">
            <span class="text-black text-xl">Pro</span><span class="text-yellow-500 text-xl">Snap</span>
        </div>
            <div class="top-menu ml-10">
                <ul class="flex space-x-4">
                    <li>
                        <a class="flex space-x-2 items-center hover:text-yellow-900 text-sm text-yellow-500"
                            href="http://127.0.0.1:8000">
                            Home
                        </a>
                    </li>

                    <li>
                        <a class="flex space-x-2 items-center hover:text-yellow-500 text-sm text-gray-500"
                            href="http://127.0.0.1:8000/portfolios">
                            My Posts
                        </a>
                    </li>

                    <li>
                        <a class="flex space-x-2 items-center hover:text-yellow-500 text-sm text-gray-500" href="{{ url('/all_posts') }}">All Posts</a>
                    </li>

                    <li>
                        <a class="flex space-x-2 items-center hover:text-yellow-500 text-sm text-gray-500"
                            href="http://127.0.0.1:8000/make_post">
                            Make a Post
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        <div id="header-right" class="flex items-center md:space-x-6">
            <div class="flex space-x-5">
                <a class="flex space-x-2 items-center hover:text-yellow-500 text-sm text-gray-500"
                    href="http://127.0.0.1:8000/login">
                    Login
                </a>
                <a class="flex space-x-2 items-center hover:text-yellow-500 text-sm text-gray-500"
                    href="http://127.0.0.1:8000/register">
                    Register
                </a>
            </div>
        </div>
    </header>


    <div class="w-full text-center py-32">
        <h1 class="text-2xl md:text-3xl font-bold text-center lg:text-5xl text-gray-700">
            Welcome to <span class="text-black">Pro</span><span class="text-yellow-500">Snap</span>
        </h1>

        <p class="text-gray-500 text-lg mt-1">Resume Showcase</p>
        <a class="px-3 py-2 text-lg text-white bg-gray-800 rounded mt-5 inline-block"
            href="http://127.0.0.1:8000/make_post">Post your Resume</a>
    </div>

    <main class="container mx-auto px-5 flex flex-grow">
        <div class="mb-10 w-full">
            <div class="mb-16">
                <h2 class="mt-16 mb-5 text-3xl text-yellow-500 font-bold">Featured Posts</h2>
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
            <hr>

            <h2 class="mt-16 mb-5 text-3xl text-yellow-500 font-bold">Latest Posts</h2>
            <div class="w-full mb-5">
                <div class="grid grid-cols-3 gap-10 gap-y-32 w-full">
                @foreach($latestPosts as $post)
                        <div class = "md:col-span-1 col-span-3">
                            <x-posts.post-card :post="$post"/>
                        </div>
                @endforeach
                </div>
            </div>
            <a class="mt-10 block text-center text-lg text-yellow-500 font-semibold"
                href="{{ url('/all_posts') }}">More
                Posts</a>
        </div>
    </main>

    <footer class="text-sm space-x-4 flex items-center border-t border-gray-100 flex-wrap justify-center py-4 ">
        <a class="text-gray-500 hover:text-yellow-500" href="{{ url('/login') }}">Login</a>
        <a class="text-gray-500 hover:text-yellow-500" href="{{ url('/register') }}">Register</a>
    </footer>
</body>