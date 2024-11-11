<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Observers\CommentObserver;
use App\Observers\LikeObserver;
use App\Observers\PostObserver;
use App\Services\CommentService;
use App\Services\CommentServiceInterface;
use App\Services\ProfileService;
use App\Services\ProfileServiceInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ProfileServiceInterface::class, ProfileService::class);
        $this->app->singleton(CommentServiceInterface::class, CommentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Like::observe(LikeObserver::class);
        Comment::observe(CommentObserver::class);
        Post::observe(PostObserver::class);
        Gate::define('administrate', function ($user) {
            return $user->isAdmin();
        });
    }
}
