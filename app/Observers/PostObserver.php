<?php

namespace App\Observers;

use App\Helpers\DispatchHelper;
use App\Jobs\SendFuturePostNotification;
use App\Models\Post;
use App\Services\NotificationServiceInterface;
use Illuminate\Support\Facades\DB;

class PostObserver
{

    /**
     * Create a new observer instance.
     *
     * @param \App\Services\NotificationServiceInterface $notificationService
     * @return void
     */
    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        // If the post is in the past, send the notification immediately
        if ($post->publication_time && $post->publication_time->isPast()) {
            $this->notificationService->handleCreation($post);
        }
        // If the post is in the future, queue the task for future execution
        elseif ($post->publication_time && $post->publication_time->isFuture()) {
            $this->dispatchNewJob($post);
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // Check if the publication time has changed (isDirty checks if the attribute is modified)
        if ($post->isDirty('publication_time')) {

            // Check if the updated publication time is in the future
            if ($post->publication_time && $post->publication_time->isFuture()) {

                // Check if a job exists for this post
                $postJob = DB::table('post_job')->where('post_id', $post->id)->first();

                if ($postJob) {
                    // If there's an existing job, remove it from the database
                    DB::table('jobs')->where('id', $postJob->job_id)->delete();
                }
                // Dispatch a new job for the updated post with the future publication time
                $this->dispatchNewJob($post);
            }
        }
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        $this->notificationService->handleDeletion($post);
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }

    protected function dispatchNewJob(Post $post): void
    {
        $delay = now()->diffInSeconds($post->publication_time, true);

        $jobId = DispatchHelper::custom_dispatch(
            (new SendFuturePostNotification($post))->delay(now()->addSeconds($delay))
        );

        DB::table('post_job')->insert([
            'post_id' => $post->id,
            'job_id' => $jobId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
