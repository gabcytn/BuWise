<?php

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

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
                return null;
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

if (!function_exists('getStartAndEndDate')) {
    function getStartAndEndDate(string $period): array
    {
        switch ($period) {
            case 'all_time':
                $start = Carbon::now()->subMillennium();
                $end = Carbon::now()->endOfMillennium();
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            case 'this_week':
                $start = Carbon::now()->startOfWeek(Carbon::SUNDAY);
                $end = Carbon::now()->endOfWeek(Carbon::SATURDAY);
                break;
            case 'last_week':
                $start = Carbon::now()->subWeek()->startOfWeek(Carbon::SUNDAY);
                $end = Carbon::now()->subWeek()->endOfWeek(Carbon::SATURDAY);
                break;
            case 'last_month':
                $start = Carbon::now()->subMonthsNoOverflow()->startOfMonth();
                $end = Carbon::now()->subMonthsNoOverflow()->endOfMonth();
                break;
            case 'last_year':
                $start = Carbon::now()->subYear()->startOfYear();
                $end = Carbon::now()->subYear()->endOfYear();
                break;
            case 'today':
                $start = Carbon::now()->startOfDay();
                $end = Carbon::now()->endOfDay();
                break;
            default:
                // this_year
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                break;
        }
        return [$start, $end];
    }
}
