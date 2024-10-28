<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});


// Main page
Route::get('/', [PostController::class, 'index'])->name('posts.index');
// Filter main page by theme
Route::get('/posts/theme/{id}', [PostController::class, 'showByTheme'])->name('posts.theme');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Single Post display
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Comments
Route::get('/posts/{post}/comment/{comment}', [CommentController::class, 'show'])->name('comments.show');
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// Admin zone
Route::middleware('can:create_post')->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin');

    Route::get('/admin/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/admin/posts/store', [PostController::class, 'store'])->name('admin.posts.store');

    Route::get('/admin/posts/{post}/edit', [PostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/admin/posts/{post}', [PostController::class, 'update'])->name('admin.posts.update');

});

// User zone
Route::middleware('auth')->group(function () {
    Route::get('/user/{username}', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update.picture');
});

require __DIR__ . '/auth.php';
