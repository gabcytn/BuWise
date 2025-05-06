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
        return !in_array($accGroupId, AccountGroup::TEMPORARY_ACCOUNTS) && in_array($accGroupId, AccountGroup::PERMANENT_ACCOUNTS);
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
}
