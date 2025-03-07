<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class UserPolicy
{
    public function viewAnyStaff(User $user): bool
    {
        return $user->role_id === Role::ACCOUNTANT;
    }

    public function viewAnyClient(User $user): bool
    {
        $roleId = $user->role_id;
        return $roleId === Role::ACCOUNTANT || $roleId === Role::LIAISON;
    }

    public function createStaff(User $user): bool
    {
        return $user->role_id === Role::ACCOUNTANT;
    }

    public function createClient(User $user): bool
    {
        $roleId = $user->role_id;
        return $roleId === Role::ACCOUNTANT || $roleId === Role::LIAISON;
    }

    public function updateStaff(User $user, User $staff): bool
    {
        return $staff->accountant_id === $user->id;
    }

    public function updateClient(User $user, User $client):bool
    {
        $roleId = $user->role_id;
        if ($roleId === Role::ACCOUNTANT)
            return $client->accountant_id === $user->id;
        elseif ($roleId === Role::LIAISON)
            return $user->accountant_id === $client->accountant_id;
        else
            return false;
    }

    public function deleteStaff(User $user, User $staff):bool
    {
        return $this->updateStaff($user, $staff);
    }

    public function deleteClient(User $user, User $client):bool
    {
        return $this->updateClient($user, $client);
    }
}
