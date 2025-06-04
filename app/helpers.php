<?php

use App\Models\Role;
use App\Models\User;

if (!function_exists('truncate')) {
    function truncate($text, $max = 25)
    {
        return strlen($text) > $max ? substr($text, 0, $max) . '...' : $text;
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date)
    {
        $res = \Carbon\Carbon::parse($date);
        return $res->format('Y-m-d');
    }
}

if (!function_exists('getClients')) {
    function getClients(User $user)
    {
        switch ($user->role_id) {
            case Role::ACCOUNTANT:
                return $user->clients;
            case Role::LIAISON:
            case Role::CLERK:
                return $user->accountant->clients;
            default:
                abort(403);
                break;
        }
    }
}

if (!function_exists('getAccountantId')) {
    function getAccountantId(User $user)
    {
        return $user->role_id === Role::ACCOUNTANT ? $user->id : $user->accountant->id;
    }
}
