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
        $clients = $related->where('role_id', '=', Role::CLIENT);
        $client_types = $clients->map(function ($client) {
            return $client->client_type;
        })->toArray();
        $type_count = $this->getTypeCount($client_types);
        $staff_count = $related->whereIn('role_id', [Role::LIAISON, Role::CLERK])->count();
        $transactions = Transaction::with('client')->whereHas('client', function ($query) use ($accId) {
            $query->where('accountant_id', '=', $accId);
        })->get();
        $invoices_count = $transactions->where('type', '=', 'invoice')->count();
        $journals_count = $transactions->where('type', '=', 'journal')->count();
        return view('dashboard', [
            'tasks' => $tasks,
            'clients_count' => $clients->count(),
            'staff_count' => $staff_count,
            'journals_count' => $journals_count,
            'invoices_count' => $invoices_count,
            'client_types' => $type_count,
        ]);
    }

    private function getTypeCount(array $client_types)
    {
        $count_map = [];
        foreach ($client_types as $type) {
            if (array_key_exists($type, $count_map))
                $count_map[$type] += 1;
            else
                $count_map[$type] = 1;
        }

        return $count_map;
    }
}
