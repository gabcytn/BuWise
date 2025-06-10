<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LedgerAccountPolicy
{
    /*
     * @return \Illuminate\Auth\Access\Response
     */
    public function chartOfAccounts(User $user)
    {
        switch ($user->role_id) {
            case Role::ACCOUNTANT:
            case Role::LIAISON:
            case Role::CLERK:
                return Response::allow();
                break;
            default:
                return Response::denyAsNotFound();
                break;
        }
    }

    /*
     * @return \Illuminate\Auth\Access\Response
     */
    public function showAccount(User $user)
    {
        return $this->chartOfAccounts($user);
    }

    public function trialBalance(User $user)
    {
        return $this->chartOfAccounts($user);
    }
}
