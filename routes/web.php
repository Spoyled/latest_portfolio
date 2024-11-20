<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\MakePostController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShowAllPosts;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmployerRegisterController;
use App\Http\Controllers\EmployerLoginController;

// Public Home Page
Route::get('/', [HomeController::class, '__invoke'])->name('home');
Route::get('/HomePage', [DashboardController::class, '__invoke'])->name('HomePage');


// Employer Authentication and Dashboard Routes
// Employer Authentication and Dashboard Routes
Route::prefix('employer')->group(function () {
    // Employer Auth Routes
    Route::get('register', [EmployerRegisterController::class, 'showRegistrationForm'])->name('employer.register');
    Route::post('register', [EmployerRegisterController::class, 'register']);
    Route::get('login', [EmployerLoginController::class, 'showLoginForm'])->name('employer.login');
    Route::post('login', [EmployerLoginController::class, 'login'])->middleware('guest:employer')->name('employer.login.post');
    Route::post('logout', [EmployerLoginController::class, 'logout'])->name('employer.logout');

    // Employer Dashboard Route
    Route::middleware(['auth:employer'])->group(function () {
        Route::get('/dashboard', function () {
            return view('employer.home'); // Matches resources/views/employer.home.blade.php
        })->name('employer.dashboard');
    });
});


// Shared Home Page and User Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // User Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard'); // Users go to 'dashboard.blade.php'

    // Other User-Specific Routes
    Route::get('/MyPosts', [PortfolioController::class, 'index'])->name('portfolios.index');
    Route::get('/Create', [MakePostController::class, 'index'])->name('make_post.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/profile', [ProfileController::class, 'show'])->name('custom.profile.show');
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/AllPosts', [ShowAllPosts::class, 'index'])->name('all_posts.index');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('post.comments.store');
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('post.comments.index');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
