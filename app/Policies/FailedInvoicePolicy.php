<?php

namespace App\Policies;

use App\Models\FailedInvoice;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FailedInvoicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        switch ($user->role_id) {
            case Role::ACCOUNTANT:
            case Role::CLERK:
            case Role::LIAISON:
                return Response::allow();
            default:
                return Response::denyAsNotFound();
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FailedInvoice $failedInvoice): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FailedInvoice $failedInvoice): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FailedInvoice $failedInvoice): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FailedInvoice $failedInvoice): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FailedInvoice $failedInvoice): bool
    {
        return false;
    }
}
