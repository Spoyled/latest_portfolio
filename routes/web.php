<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\MakePostController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShowAllPosts;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmployerRegisterController;
use App\Http\Controllers\EmployerLoginController;
use App\Http\Controllers\EmployerDashboardController;
// Public Home Page
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

Route::get('/', function () {
    // Redirect authenticated employers to their dashboard
    if (Auth::guard('employer')->check()) {
        return redirect()->route('employer.dashboard');
    }

    // Redirect authenticated users to HomePage
    if (Auth::check()) {
        return redirect()->route('HomePage');
    }

    // Fetch posts for guests - only job offers
    $featuredPosts = Post::where('post_type', 'job_offer')
                         ->where('featured', true)
                         ->latest('published_at')
                         ->take(3)
                         ->get();

    $latestPosts = Post::where('post_type', 'job_offer')
                       ->latest('published_at')
                       ->take(9)
                       ->get();

    return view('home', compact('featuredPosts', 'latestPosts'));
})->name('home');



// Employer Authentication and Dashboard Routes
// Employer Authentication and Dashboard Routes
Route::prefix('employer')->group(function () {
    // Employer Auth Routes
    Route::get('register', [EmployerRegisterController::class, 'showRegistrationForm'])->name('employer.register');
    Route::post('register', [EmployerRegisterController::class, 'register']);
    Route::get('login', [EmployerLoginController::class, 'showLoginForm'])->name('employer.login');
    Route::post('login', [EmployerLoginController::class, 'login'])->name('employer.login.post');
    Route::post('logout', [EmployerLoginController::class, 'logout'])->name('employer.logout');

    // Employer Dashboard and Authenticated Routes
    Route::middleware(['auth:employer'])->group(function () {
        Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('employer.dashboard');
        Route::get('/MyPosts', [PortfolioController::class, 'index'])->name('employer.portfolios');
        Route::get('/Create', [MakePostController::class, 'index'])->name('employer.make_post');
        Route::post('/posts', [PostController::class, 'store'])->name('employer.posts.store');
        Route::get('/profile', [CustomProfileController::class, 'show'])->name('employer.custom.profile.show');
        Route::post('/profile/update', [CustomProfileController::class, 'update'])->name('employer.custom.profile.update');
        Route::get('/posts', [PostController::class, 'index'])->name('employer.posts');
        Route::get('/posts/{post}', [PostController::class, 'show'])->name('employer.posts.show');
        Route::get('/AllPosts', [ShowAllPosts::class, 'index'])->name('employer.all_posts');
        Route::post('/logout', [EmployerLoginController::class, 'logout'])->name('employer.logout');
        Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('employer.post.comments.store');
        Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('employer.post.comments');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('employer.comments.destroy');
        Route::get('{post}/applicants', [PostController::class, 'viewApplicants'])->name('posts.applicants')->middleware('auth:employer');
    });
});

// Shared Home Page and User Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'user.guard',
])->group(function () {
    // User Dashboard

    Route::get('/HomePage', [DashboardController::class, '__invoke'])->name('HomePage');
    // Other User-Specific Routes
    Route::get('/MyPosts', [PortfolioController::class, 'index'])->name('portfolios.index');
    Route::get('/Create', [MakePostController::class, 'index'])->name('make_post.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/profile', [CustomProfileController::class, 'show'])->name('custom.profile.show');
    Route::post('/profile/update', [CustomProfileController::class, 'update'])->name('custom.profile.update');
    Route::post('/profile/upload-cv', [CustomProfileController::class, 'uploadCV'])->name('profile.upload-cv');
    Route::post('/profile/generate-cv', [CustomProfileController::class, 'generateCV'])->name('profile.generate-cv');
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/AllPosts', [ShowAllPosts::class, 'index'])->name('all_posts.index');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('post.comments.store');
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('post.comments.index');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/posts/{post}/apply', [PostController::class, 'apply'])->name('posts.apply');

});
