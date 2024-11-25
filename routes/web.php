<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserNotificationPreferenceController;
use Illuminate\Support\Facades\Route;

// =======================================
// Public Routes
// =======================================

// Main Page: Display all posts
Route::get('/blog', [PostController::class, 'index'])->name('posts.index');
Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Filtered Main Page: Display posts by theme
Route::get('/blog/theme/{id}', [PostController::class, 'showByTheme'])->name('posts.theme');

// Display a Single Post
Route::get('/blog/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/blog/{post}/redirect', [PostController::class, 'showRedirect'])->name('posts.show.redirect')->middleware('auth');

// Display a Single Comment on a Post
Route::get('/blog/{post}/commentaire/{comment}', [CommentController::class, 'show'])->name('comments.show');
Route::get('/blog/{post}/commentaire/{comment}/redirect', [CommentController::class, 'showRedirect'])->name('comments.show.redirect')->middleware('auth');

// Load more comments for a post
Route::get('/blog/{post}/comments/load-more-comments', [CommentController::class, 'loadMoreComments'])->name('comments.loadMore');

// Load more replies for a comment
Route::get('/commentaires/{comment}/load-more-replies', [CommentController::class, 'loadMoreReplies'])->name('comments.loadMoreReplies');


// =======================================
// Authenticated User Routes
// =======================================
Route::middleware('auth')->group(function () {

    // User Profile Routes
    Route::get('/profil/{username}', [ProfileController::class, 'show'])->name('profile.show');
    // Routes to fetch more comments or posts for a user
    Route::get('/profil/{username}/commentaires', [ProfileController::class, 'fetchMoreComments'])->name('profile.fetchMoreComments');
    Route::get('/profil/{username}/commentaires-aimes', [ProfileController::class, 'fetchMoreLikedComments'])->name('profile.fetchMoreLikedComments');
    Route::get('/profil/{username}/posts-aimes', [ProfileController::class, 'fetchMoreLikedPosts'])->name('profile.fetchMoreLikedPosts');

    // Profile Settings
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profil/description', [ProfileController::class, 'updateDescription'])->name('profile.update.description');
    Route::delete('/mon-profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Profile Picture Update
    Route::put('/mon-profil/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update.picture');

    // Notifications Page
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    // Mark notifications as read
    Route::get('/notifications/lu', [NotificationController::class, 'markAllAsRead'])->name('notifications.read');


    // Notification Settings
    Route::put('/notifications/preferences', [UserNotificationPreferenceController::class, 'update'])->name('notifications.preferences.update');
    Route::post('/notifications/preferences/mute/{comment_content}', [UserNotificationPreferenceController::class, 'muteComment'])->name('notifications.preferences.mute');
    Route::post('/notifications/preferences/unmute/{comment_content}', [UserNotificationPreferenceController::class, 'unmuteComment'])->name('notifications.preferences.unmute');

    // Thread page
    Route::get('/profile/thread', [ProfileController::class, 'thread'])->name('profile.thread');

    // Comment Actions
    Route::post('/commentaires', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/commentaires/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/commentaires/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::delete('/commentaires/{comment}/unlike', [CommentController::class, 'unlike'])->name('comments.unlike');

    // Post Actions
    Route::post('/blog/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/blog/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');

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
    Route::get('/admin/orphans', [AdminController::class, 'deleteOrphanedContents'])->name('admin.delete.orphans');

    // Post Management in Admin Zone
    Route::get('/admin/posts/creer', [PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/admin/posts/creer', [PostController::class, 'store'])->name('admin.posts.store');
    Route::get('/admin/posts/{post}/editer', [PostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/admin/posts/{post}', [PostController::class, 'update'])->name('admin.posts.update');

    // User Management in Admin Zone
    Route::get('/admin/profils/chercher', [ProfileController::class, 'search'])->name('admin.users.search');
    Route::get('/admin/profils/{user}/changer-role/{role}', [ProfileController::class, 'changeRole'])->name('admin.users.changeRole');
    Route::get('/admin/profils/{user}/supprimer', [ProfileController::class, 'adminDestroy'])->name('admin.users.delete');

});

require __DIR__ . '/auth.php';
