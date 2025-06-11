<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $accId = getAccountantId($user);
        $clients = Cache::remember("$accId-clients", 3600, function () use ($user) {
            return getClients($user);
        });
        $staff = $user->staff;
        return view('calendar.index', [
            'clients' => $clients,
            'staff' => $staff,
        ]);
    }
}
