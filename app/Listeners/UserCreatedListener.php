<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Role;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class UserCreatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        if ($event->user->role_id === Role::CLIENT) {
            Cache::delete($event->creator->id . '-clients');
            $clients = getClients($event->creator);
            Cache::set($event->creator->id . '-clients', $clients, 3600);
        }
        $event->user->sendEmailVerificationNotification();
    }
}
