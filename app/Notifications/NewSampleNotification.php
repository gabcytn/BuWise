<?php

namespace App\Notifications;

use App\Models\Notification as ModelsNotification;
use App\Models\User;
use Illuminate\Notifications\Notification;
use YieldStudio\LaravelExpoNotifier\Dto\ExpoMessage;
use YieldStudio\LaravelExpoNotifier\ExpoNotificationsChannel;

class NewSampleNotification extends Notification
{
    public function __construct(
        private User $user
    ) {
        //
    }

    public function via($notifiable): array
    {
        return [ExpoNotificationsChannel::class];
    }

    public function toExpoNotification($notifiable): ExpoMessage
    {
        $n = ModelsNotification::create([
            'title' => 'Failed Invoice',
            'user_id' => $this->user->id,
            'description' => 'The invoice you have submitted does not look like an invoice. Please resubmit.'
        ]);
        $expo_token = $notifiable->expoTokens()->orderByDesc('created_at')->first();
        return (new ExpoMessage())
            ->to([$expo_token->value])
            ->title($n->title)
            ->body($n->description)
            ->channelId('default');
    }
}
