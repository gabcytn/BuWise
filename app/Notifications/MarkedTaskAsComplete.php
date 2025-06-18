<?php

namespace App\Notifications;

use App\Models\Notification as ModelsNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MarkedTaskAsComplete extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private User $receiver,
        private User $assignedTo,
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
        $n = ModelsNotification::create([
            'title' => 'Marked Task as Complete',
            'user_id' => $this->receiver->id,
            'description' => $this->assignedTo->name . ' marked your task as complete',
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
