<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\OrganizationMember;
use App\Models\Role;
use App\Models\User;
use App\Notifications\LiaisonCreatedClient;
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
            $user_created = $event->user;
            $creator = User::find($user_created->created_by);
            if (!$creator)
                throw new \Exception('Creator of user is non-existent');
            $accId = getAccountantId($creator);
            $accountant = User::find($accId);
            if ($user_created->role_id === Role::CLIENT) {
                $clients = getClients($accountant);
                Cache::put("$accId-clients", $clients, 3600);
                Log::info('Successfully updated clients cache');
            }

            $organization = $creator->organization;
            OrganizationMember::create([
                'user_id' => $user_created->id,
                'organization_id' => $organization->id,
            ]);

            $user_created->onboarded = true;
            $user_created->save();
            Log::info('Successfully created new member of organization and marked as onboarded');

            // notify accountant that a client has been created
            if ($creator->role_id === Role::LIAISON)
                $accountant->notify(new LiaisonCreatedClient($accountant, $creator));

            $user_created->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            Log::warning('Error handling event listener: ' . $e->getMessage());
        }
    }
}
