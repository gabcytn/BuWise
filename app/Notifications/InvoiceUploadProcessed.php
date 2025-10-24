<?php

namespace App\Notifications;

use App\Models\Notification as ModelsNotification;
use Illuminate\Notifications\Notification;
use YieldStudio\LaravelExpoNotifier\Dto\ExpoMessage;
use YieldStudio\LaravelExpoNotifier\ExpoNotificationsChannel;

class InvoiceUploadProcessed extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct() {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return [ExpoNotificationsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toExpoNotifications(object $notifiable): ExpoMessage
    {
        $notif = ModelsNotification::create([
            'title' => 'Invoice Processed',
            'user_id' => $notifiable->id,
            'description' => 'The invoice you submitted has been processed.'
        ]);
        $expo_token = $notifiable->expoTokens()->orderByDesc('id')->first();

        return (new ExpoMessage())
            ->to($expo_token->value)
            ->title($notif->title)
            ->body($notif->description)
            ->channelId('default');
    }
}
