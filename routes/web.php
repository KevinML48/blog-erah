<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// =======================================
// Public Routes
// =======================================

// Main Page: Display all posts
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// Filtered Main Page: Display posts by theme
Route::get('/posts/theme/{id}', [PostController::class, 'showByTheme'])->name('posts.theme');

// Display a Single Post
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Display a Single Comment on a Post
Route::get('/posts/{post}/comment/{comment}', [CommentController::class, 'show'])->name('comments.show');

// Load more comments for a post
Route::get('/posts/{post}/comments/load-more-comments', [CommentController::class, 'loadMoreComments'])->name('comments.loadMore');

// Load more replies for a comment
Route::get('/comments/{comment}/load-more-replies', [CommentController::class, 'loadMoreReplies'])->name('comments.loadMoreReplies');


// =======================================
// Authenticated User Routes
// =======================================
Route::middleware('auth')->group(function () {

    // User Profile Routes
    Route::get('/user/{username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/user/{username}/comments', [ProfileController::class, 'comments'])->name('profile.comments');
    Route::get('/user/{username}/likes/comments', [ProfileController::class, 'commentLikes'])->name('profile.likes.comments');
    Route::get('/user/{username}/likes/posts', [ProfileController::class, 'postLikes'])->name('profile.likes.posts');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile Picture Update
    Route::put('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update.picture');

    // Thread page
    Route::get('/profile/thread', [ProfileController::class, 'thread'])->name('profile.thread');

    // Comment Actions
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::delete('/comments/{comment}/unlike', [CommentController::class, 'unlike'])->name('comments.unlike');

    // Post Actions
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');

    // Follow Actions
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('user.follow');
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('user.unfollow');

    // Tenor GIF Search
    Route::get('/tenor/search', [CommentController::class, 'searchTenor'])->name('tenor.search');
});


// =======================================
// Admin Routes
// =======================================
Route::middleware('can:administrate')->group(function () {

    // Admin Dashboard
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin');

    // Post Management in Admin Zone
    Route::get('/admin/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/admin/posts/store', [PostController::class, 'store'])->name('admin.posts.store');
    Route::get('/admin/posts/{post}/edit', [PostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/admin/posts/{post}', [PostController::class, 'update'])->name('admin.posts.update');

    // User Management in Admin Zone
    Route::get('/admin/users/search', [ProfileController::class, 'search'])->name('admin.users.search');
    Route::get('/admin/users/{user}/change-role/{role}', [ProfileController::class, 'changeRole'])->name('admin.users.changeRole');
    Route::get('/admin/users/{user}/delete', [ProfileController::class, 'adminDestroy'])->name('admin.users.delete');

});

require __DIR__ . '/auth.php';
