<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
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
use App\Models\Post;

// ------------------------------------------------
// Public Home Page
// ------------------------------------------------
Route::get('/', function () {
    // If employer is logged in, send them to employer dashboard
    if (Auth::guard('employer')->check()) {
        return redirect()->route('employer.dashboard');
    }
    // If normal user is logged in, send them to HomePage
    if (Auth::check()) {
        return redirect()->route('HomePage');
    }
    // If guest, show homepage with featured posts
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

// ------------------------------------------------
// Employer Authentication + Routes
// ------------------------------------------------
Route::prefix('employer')->group(function () {
    // -- Employer Auth Pages --
    Route::get('/register', [EmployerRegisterController::class, 'showRegistrationForm'])->name('employer.register');
    Route::post('/register', [EmployerRegisterController::class, 'register']);
    Route::get('/login', [EmployerLoginController::class, 'showLoginForm'])->name('employer.login');
    Route::post('/login', [EmployerLoginController::class, 'login'])->name('employer.login.post');
    Route::post('/logout', [EmployerLoginController::class, 'logout'])->name('employer.logout');

    // -- Employer-Only Routes (need auth:employer) --
    Route::middleware('auth:employer')->group(function () {

        // Employer Dashboard
        Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('employer.dashboard');

        // Employer’s own posts
        Route::get('/MyPosts', [PortfolioController::class, 'index'])->name('employer.portfolios');
        Route::get('/Create', [MakePostController::class, 'index'])->name('employer.make_post');
        Route::post('/posts', [PostController::class, 'store'])->name('employer.posts.store');

        Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('employer.posts.edit');
        Route::put('/posts/{post}', [PostController::class, 'update'])->name('employer.posts.update');
        

        // Profile
        Route::get('/profile', [CustomProfileController::class, 'show'])->name('employer.custom.profile.show');
        Route::post('/profile/update', [CustomProfileController::class, 'update'])->name('employer.custom.profile.update');

        // Employer Posts
        Route::get('/posts', [PostController::class, 'index'])->name('employer.posts');
        Route::get('/posts/{post}', [PostController::class, 'show'])->name('employer.posts.show');
        Route::get('/AllPosts', [ShowAllPosts::class, 'index'])->name('employer.all_posts');
        Route::patch('/posts/{post}/applicants/{user}/recruit', [PostController::class, 'markAsRecruited'])->name('applicants.recruit');
        Route::patch('/posts/{post}/applicants/{user}/decline', [PostController::class, 'declineApplicant'])->name('applicants.decline');



        // Comments on employer posts
        Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('employer.post.comments.store');
        Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('employer.post.comments');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('employer.comments.destroy');

        // View applicants
        Route::get('{post}/applicants', [PostController::class, 'viewApplicants'])
            ->name('posts.applicants');

        // Toggle post active/inactive
        Route::delete('/posts/{id}', [PostController::class, 'destroy'])
            ->name('employer.posts.destroy');
    });
});


Route::post('/posts/{post}/close', [PostController::class, 'close'])
    ->middleware(['web', 'auth:employer'])
    ->name('posts.close');


Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
     ->name('post.comments.store');


// ------------------------------------------------
// Normal User Routes (Jetstream + your user.guard)
// ------------------------------------------------
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'user.guard',  // ensure this is set up properly
])->group(function () {

    // Example user dashboard
    Route::get('/HomePage', [DashboardController::class, '__invoke'])->name('HomePage');

    // User’s own posts (if you allow that)
    Route::get('/MyPosts', [PortfolioController::class, 'index'])->name('portfolios.index');
    Route::get('/Create', [MakePostController::class, 'index'])->name('make_post.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    // User profile
    Route::get('/profile', [CustomProfileController::class, 'show'])->name('custom.profile.show');
    Route::post('/profile/update', [CustomProfileController::class, 'update'])->name('custom.profile.update');
    Route::post('/profile/upload-cv', [CustomProfileController::class, 'uploadCV'])->name('profile.upload-cv');
    Route::post('/profile/generate-cv', [CustomProfileController::class, 'generateCV'])->name('profile.generate-cv');

    Route::get('/posts/{post}/edit', [PostController::class, 'editUser'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'updateUser'])->name('posts.update');

    // User sees posts (for example, only 'job_offer' posts)
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/AllPosts', [ShowAllPosts::class, 'index'])->name('all_posts.index');

    // Comments (for normal user)
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('post.comments.index');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Apply to a job post
    Route::post('/posts/{post}/apply', [PostController::class, 'apply'])->name('posts.apply');

    // Inside Route::middleware([... 'user.guard', ...])...
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])
            ->name('posts.destroy');


});
