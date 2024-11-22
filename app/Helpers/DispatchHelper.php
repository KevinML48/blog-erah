<?php

namespace App\Helpers;

use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;

class DispatchHelper
{
    /**
     * Dispatch a job to the Laravel queue.
     *
     * @param  \Illuminate\Contracts\Queue\ShouldQueue|\Illuminate\Foundation\Bus\Dispatchable  $job
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public static function custom_dispatch($job)
    {
        if (!is_subclass_of($job, ShouldQueue::class)) {
            throw new \InvalidArgumentException('The provided job must implement ShouldQueue.');
        }

        // Dispatch the job using the Laravel dispatcher
        return app(Dispatcher::class)->dispatch($job);
    }


}
