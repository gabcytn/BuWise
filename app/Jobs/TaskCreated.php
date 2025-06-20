<?php

namespace App\Jobs;

use App\Models\Task;
use App\Notifications\NewTask;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TaskCreated implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Task $task
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->task->user->notify(new NewTask($this->task->assigned_to, $this->task->created_by));
    }
}
