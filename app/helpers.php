<?php

use App\Models\Role;
use App\Models\User;

if (!function_exists('truncate')) {
    function truncate($text, $max = 50)
    {
        return strlen($text) > 50 ? substr($text, 0, $max) . '...' : $text;
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date)
    {
        $res = \Carbon\Carbon::parse($date);
        return $res->format('F d, Y');
    }
}

if (!function_exists('getClients')) {
    function getClients(User $user)
    {
        if ($user->role_id === Role::ACCOUNTANT) {
            return $user->clients;
        } else if ($user->role_id !== Role::CLIENT) {
            return $user->accountant->clients;
        }
        return null;
    }
}
