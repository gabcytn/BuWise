<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Role;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        try {
            if ($event->user->role_id === Role::CLIENT) {
                $clients = getClients($event->creator);
                Cache::put($event->creator->id . '-clients', $clients, 3600);
                Log::info('Successfully updated clients cache');
            }
            $event->user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            Log::warning('Error handling event listener: ' . $e->getMessage());
        }
    }
}
