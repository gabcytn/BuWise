<?php

namespace App\Notifications;

use App\Models\Notification as ModelsNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissingInvoice extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private User $user,
        private array $invoices
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
        // return ['mail', 'broadcast'];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $n = ModelsNotification::create([
            'title' => 'Missing Invoice',
            'user_id' => $this->user->id,
            'description' => 'The following invoices are missing from your previous excel file upload: ' . implode(', ', $this->invoices),
        ]);
        return new BroadcastMessage([
            'id' => $n->id,
            'title' => $n->title,
            'description' => $n->description,
            'created_at' => $n->created_at,
        ]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Missing Invoices')
            ->greeting('Greetings, ' . $this->user->name . '!')
            ->line('This is to inform that the file you uploaded has missing invoice numbers namely: ' . implode(', ', $this->invoices))
            ->action('Go to BuWise', url('/journal-entries'))
            ->line('Thank you for using our application!');
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
