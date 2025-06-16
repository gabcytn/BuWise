<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
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

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role_id === Role::ACCOUNTANT;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        $validRole = in_array($user->role_id, [Role::ACCOUNTANT, Role::LIAISON, Role::CLERK]);
        return $validRole && ($task->created_by === $user->id || $task->assigned_to === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        $validRole = in_array($user->role_id, [Role::ACCOUNTANT, Role::LIAISON, Role::CLERK]);
        return $validRole && $task->created_by === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
