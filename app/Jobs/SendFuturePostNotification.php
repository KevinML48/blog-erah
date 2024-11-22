<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\NotificationServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendFuturePostNotification implements ShouldQueue
{
    use Queueable;
    protected Post $post;
    public $jobId;

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationServiceInterface $notificationService): void
    {
        $this->jobId = $this->job->getJobId();
        $notificationService->handleCreation($this->post);
    }
}
