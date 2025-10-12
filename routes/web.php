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
Route::get('/', HomeController::class)->name('home');

// ------------------------------------------------
// Employer Authentication + Routes
// ------------------------------------------------
Route::prefix('employer')->group(function () {
    // Auth pages
    Route::get('/register', [EmployerRegisterController::class, 'showRegistrationForm'])->name('employer.register');
    Route::post('/register', [EmployerRegisterController::class, 'register']);
    Route::get('/login', [EmployerLoginController::class, 'showLoginForm'])->name('employer.login');
    Route::post('/login', [EmployerLoginController::class, 'login'])->name('employer.login.post');
    Route::post('/logout', [EmployerLoginController::class, 'logout'])->name('employer.logout');

    // Employer-only
    Route::middleware('auth:employer')->group(function () {

        // Dashboard
        Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('employer.dashboard');

        // Profile (this was missing; header needs it)
        Route::get('/profile', [CustomProfileController::class, 'show'])->name('employer.custom.profile.show');
        Route::post('/profile/update', [CustomProfileController::class, 'update'])->name('employer.custom.profile.update');

        // Create / list employer posts (no model binding)
        Route::get('/MyPosts', [PortfolioController::class, 'index'])->name('employer.portfolios');
        Route::get('/Create', [MakePostController::class, 'index'])->name('employer.make_post');
        Route::post('/posts', [PostController::class, 'store'])->name('employer.posts.store');
        Route::get('/posts', [PostController::class, 'index'])->name('employer.posts');

        // Show a post page under /employer (uses API inside controller; no binding)
        Route::get('/posts/{post}', [PostController::class, 'show'])->name('employer.posts.show');

        // Edit/Update (IDs, not Post binding)
        Route::get('/posts/{postId}/edit', [PostController::class, 'edit'])->name('employer.posts.edit');
        Route::put('/posts/{postId}',      [PostController::class, 'update'])->name('employer.posts.update');

        // Applicants (IDs, not Post/User binding)
        Route::get('/{postId}/applicants', [PostController::class, 'viewApplicants'])->name('posts.applicants');
        Route::patch('/posts/{postId}/applicants/{userId}/recruit', [PostController::class, 'markAsRecruited'])->name('applicants.recruit');
        Route::patch('/posts/{postId}/applicants/{userId}/decline', [PostController::class, 'declineApplicant'])->name('applicants.decline');

        // All posts list for employer (resumes)
        Route::get('/AllPosts', [ShowAllPosts::class, 'index'])->name('employer.all_posts');

        // Delete (if you still need it; beware local Post FK assumptions)
        Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('employer.posts.destroy');
    });
});

// Close post (ID param; guarded)
Route::post('/posts/{postId}/close', [PostController::class, 'close'])
    ->middleware(['web', 'auth:employer'])
    ->name('posts.close');


Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
     ->name('post.comments.store');


// Then your normal public post routes:
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/AllPosts', [ShowAllPosts::class, 'index'])->name('all_posts.index');



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
    Route::get('/profile/download-cv/{version?}', [CustomProfileController::class, 'downloadCV'])->name('profile.download-cv');
    Route::post('/profile/generate-cv', [CustomProfileController::class, 'generateCv'])->name('profile.generate-cv');
    Route::post('/profile/analyze-cv', [CustomProfileController::class, 'analyzeCv'])->name('profile.analyze-cv');
    Route::delete('/profile/cv-versions/{version}', [CustomProfileController::class, 'destroyCvVersion'])->name('profile.cv-versions.destroy');

    Route::get('/posts/{post}/edit', [PostController::class, 'editUser'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'updateUser'])->name('posts.update');

    // User sees posts (for example, only 'job_offer' posts)

    // Comments (for normal user)mi
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('post.comments.index');

    // Apply to a job post
    Route::post('/posts/{postId}/apply', [PostController::class, 'apply'])->name('posts.apply');

    // Inside Route::middleware([... 'user.guard', ...])...
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])
            ->name('posts.destroy');

});
