<?php

namespace App\Policies;

use App\Models\AccountGroup;
use App\Models\LedgerAccount;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LedgerAccountPolicy
{
    /**
     * @return bool
     */
    public function setInitialBalance(User $user, LedgerAccount $ledgerAccount)
    {
        $accGroupId = $ledgerAccount->account_group_id;

        return AccountGroup::IS_PERMANENT[$accGroupId];
    }

    /*
     * @return \Illuminate\Auth\Access\Response
     */
    public function chartOfAccounts(User $user)
    {
        return $user->role_id !== Role::CLIENT ? Response::allow() : Response::denyAsNotFound();
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
