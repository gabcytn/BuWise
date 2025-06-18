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
        $start_of_week = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $end_of_week = Carbon::now()->endOfWeek(Carbon::SATURDAY);
        $tasks = Task::whereBetween('end_date', [$start_of_week, $end_of_week])
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($tasks as $task) {
            $user = User::find($task->assigned_to);
            $user->notify(new TaskReminderNotification($user, $task));
        }
    }
}
