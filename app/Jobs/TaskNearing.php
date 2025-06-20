<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskReminderNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TaskNearing implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $start = Carbon::now()->format('Y-m-d');
        $end = Carbon::now()->addWeek()->format('Y-m-d');
        $tasks = Task::whereBetween('end_date', [$start, $end])
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($tasks as $task) {
            $user = User::find($task->assigned_to);
            $user->notify(new TaskReminderNotification($user, $task));
        }
    }
}
