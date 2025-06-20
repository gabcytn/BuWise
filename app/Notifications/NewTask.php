<?php

namespace App\Notifications;

use App\Models\Notification as ModelsNotification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Broadcast;

class NewTask extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private string $task_assigned_to_id,
        private string $task_creator_id
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $creator = User::find($this->task_creator_id);
        $n = ModelsNotification::create([
            'title' => 'New Task Assigned',
            'user_id' => $this->task_assigned_to_id,
            'description' => $creator->name . ' just assigned you a task',
        ]);
        return new BroadcastMessage([
            'id' => $n->id,
            'title' => $n->title,
            'description' => $n->description,
            'created_at' => $n->created_at,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
