<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $accId = getAccountantId($user);
        $tasks = Task::where('assigned_to', '=', $user->id)
            ->orderBy('end_date')
            ->get();
        $related = User::where('accountant_id', '=', $accId)->get();
        $clients_count = $related->where('role_id', '=', Role::CLIENT)->count();
        $staff_count = $related->whereIn('role_id', [Role::LIAISON, Role::CLERK])->count();
        $transactions = Transaction::with('client')->whereHas('client', function ($query) use ($accId) {
            $query->where('accountant_id', '=', $accId);
        })->get();
        $invoices_count = $transactions->where('type', '=', 'invoice')->count();
        $journals_count = $transactions->where('type', '=', 'journal')->count();
        return view('dashboard', [
            'tasks' => $tasks,
            'clients_count' => $clients_count,
            'staff_count' => $staff_count,
            'journals_count' => $journals_count,
            'invoices_count' => $invoices_count,
        ]);
    }
}
