<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role_id !== Role::BOT)
            abort(404);
        $clients = User::where('role_id', '=', Role::CLIENT)->get();
        return view('invoices.bot-create', [
            'clients' => $clients,
        ]);
    }
}
