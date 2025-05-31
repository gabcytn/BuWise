<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     * @return \Illuminate\Auth\Access\Response;
     */
    public function viewAny(User $user)
    {
        return $user->role_id !== Role::CLIENT
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can view the model.
     * @return \Illuminate\Auth\Access\Response;
     */
    public function view(User $user, Transaction $transaction, array $typesToAllow, string $typeOfTransaction)
    {
        $roleId = $user->role_id;
        if ($roleId === Role::ACCOUNTANT) {
            return $transaction->client->accountant_id === $user->id && in_array($typeOfTransaction, $typesToAllow)
                ? Response::allow()
                : Response::denyAsNotFound();
        } else if (($roleId === Role::LIAISON || $roleId == Role::CLERK) && in_array($typeOfTransaction, $typesToAllow)) {
            $accId = $user->accountant_id;
            return $transaction->client->accountant_id === $accId
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     * @return \Illuminate\Auth\Access\Response;
     */
    public function create(User $user)
    {
        return $user->role_id !== Role::CLIENT
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can update the model.
     * @return \Illuminate\Auth\Access\Response;
     */
    public function update(User $user, Transaction $transaction)
    {
        $roleId = $user->role_id;
        if ($roleId === Role::ACCOUNTANT) {
            return $transaction->client->accountant_id === $user->id
                ? Response::allow()
                : Response::denyAsNotFound();
        } else if ($roleId === Role::LIAISON || $roleId == Role::CLERK) {
            $accId = $user->accountant_id;
            return $transaction->client->accountant_id === $accId
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     * @return \Illuminate\Auth\Access\Response;
     */
    public function delete(User $user, Transaction $transaction)
    {
        return $this->update($user, $transaction);
    }

    /**
     * Determine whether the user can restore the model.
     * @return \Illuminate\Auth\Access\Response;
     */
    public function changeStatus(User $user, Transaction $transaction)
    {
        return $this->update($user, $transaction);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Transaction $transaction): bool
    {
        return false;
    }
}
