<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThemeController;
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
Route::get('/blog/theme/{slug}', [PostController::class, 'showByTheme'])->name('posts.theme');

// Display a Single Post
Route::get('/blog/{post}', [PostController::class, 'show'])->name('posts.show');
// Routes to redirect an unauthenticated user to the right section after login
Route::get('/blog/{post}/redirection/commentaires', [PostController::class, 'showRedirectComment'])->name('posts.show.redirect.comments')->middleware('auth');
Route::get('/blog/{post}/redirection/jaime', [PostController::class, 'showRedirectLike'])->name('posts.show.redirect.like')->middleware('auth');

// Display a Single Comment on a Post
Route::get('/blog/{post}/commentaire/{comment}', [CommentController::class, 'show'])->name('comments.show');
Route::get('/blog/{post}/commentaire/{comment}/redirection', [CommentController::class, 'showRedirect'])->name('comments.show.redirect')->middleware('auth');
Route::get('/blog/{post}/commentaire/{comment}/redirection/jaime', [CommentController::class, 'showRedirectLike'])->name('comments.show.redirect.like')->middleware('auth');

// Load more comments for a post
Route::get('/blog/{post}/comments/load-more-comments', [CommentController::class, 'loadMoreComments'])->name('comments.loadMore');

// Load more replies for a comment
Route::get('/commentaires/{comment}/load-more-replies', [CommentController::class, 'loadMoreReplies'])->name('comments.loadMoreReplies');

// Routes to check the availability of credentials
Route::get('/check-username', [ProfileController::class, 'checkUsername'])->name('check-username');
Route::get('/check-email', [ProfileController::class, 'checkEmail'])->name('check-email');

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
Route::middleware('can:administrate')->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('orphans', [AdminController::class, 'deleteOrphanedContents'])->name('delete.orphans');

    // Post Management in Admin Zone
    Route::get('posts/creer', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts/creer', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}/editer', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');

    // User Management in Admin Zone
    Route::get('profils/chercher', [ProfileController::class, 'search'])->name('users.search');
    Route::get('profils/{user}/changer-role/{role}', [ProfileController::class, 'changeRole'])->name('users.changeRole');
    Route::get('profils/{user}/supprimer', [ProfileController::class, 'adminDestroy'])->name('users.delete');

    // Theme management
    Route::get('themes/create', [ThemeController::class, 'create'])->name('themes.create');
    Route::post('themes', [ThemeController::class, 'store'])->name('themes.store');
    Route::get('themes/{theme}/edit', [ThemeController::class, 'edit'])->name('themes.edit');
    Route::put('themes/{theme}', [ThemeController::class, 'update'])->name('themes.update');
    Route::delete('themes/{theme}', [ThemeController::class, 'destroy'])->name('themes.destroy');

});

require __DIR__ . '/auth.php';
