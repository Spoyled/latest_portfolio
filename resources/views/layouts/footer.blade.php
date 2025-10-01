<footer
    class="text-base space-x-6 flex items-center justify-center py-4 bg-blue-900 border-t border-yellow-500 text-white">

    @if(auth('employer')->check())
        <!-- Links for Employers -->
        <a class="hover:text-yellow-400" href="{{ route('employer.custom.profile.show') }}">My Profile</a>
        <a class="hover:text-yellow-400" href="{{ route('employer.portfolios') }}">My Posts</a>
        <a class="hover:text-yellow-400" href="{{ route('employer.dashboard') }}">Dashboard</a>
    @else(auth()->check())
        <!-- Links for Simple Users -->
        <a class="hover:text-yellow-400" href="{{ route('custom.profile.show') }}">My Profile</a>
        <a class="hover:text-yellow-400" href="{{ url('/MyPosts') }}">My Posts</a>
        <a class="hover:text-yellow-400" href="{{ route('HomePage') }}">Home Page</a>
    @endif
</footer>

</body>