<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\MakePostController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShowAllPosts;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmployerRegisterController;
use App\Http\Controllers\EmployerLoginController;

Route::get('/', HomeController::class)->middleware('auth.redirect');

#Employer
Route::get('/EmployerRegister', [EmployerRegisterController::class, 'showRegistrationForm'])->name('employer.register');
Route::post('/EmployerRegister', [EmployerRegisterController::class, 'register']);

Route::get('EmployerLogin', [EmployerLoginController::class, 'showLoginForm'])->name('employer.login');
Route::post('EmployerLogin', [EmployerLoginController::class, 'login'])->name('employer.login.post');
Route::post('EmployerLogout', [EmployerLoginController::class, 'logout'])->name('employer.logout');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    
])->group(function () {
    Route::get('/HomePage', DashboardController::class)->name('HomePage');
    Route::get('/MyPosts', [PortfolioController::class, 'index'])->name('portfolios.index');
    Route::get('/Create', [MakePostController::class, 'index'])->name('make_post.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/profile', [ProfileController::class, 'show'])->name('custom.profile.show');
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/AllPosts', [ShowAllPosts::class, 'index'])->name('all_posts.index');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('post.comments.store');
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('post.comments.index');
    Route::get('/employer/dashboard', [EmployerDashboardController::class, 'index'])->name('employer.dashboard');
});
