<?php

namespace App\Listeners;

use App\Events\ClientDeleted;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class ClientDeletedListener implements ShouldQueue
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
    public function handle(ClientDeleted $event): void
    {
        $accountant_id = $event->accountant_id;
        $accountant = User::find($accountant_id);

        $clients = getClients($accountant);
        Cache::put("$accountant_id-clients", $clients, 3600);
    }
}
