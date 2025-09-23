<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role_id === Role::BOT)
            abort(404);
        $accId = getAccountantId($user);
        $tasks = Task::where('assigned_to', '=', $user->id)
            ->where('status', '!=', 'completed')
            ->orderBy('end_date')
            ->get();
        $related = User::where('accountant_id', '=', $accId)->get();
        $clients = $related->where('role_id', '=', Role::CLIENT)->where('suspended', '=', 0);
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

    public function getTasks(Request $request)
    {
        $user = $request->user();
        $accountant_id = getAccountantId($user);
        $tasks = Task::with('user')
            ->where('created_by', '=', $accountant_id)
            ->whereBetween('completed_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->orderBy('completed_at')
            ->get();
        $months = [];
        foreach ($tasks as $task) {
            if ($task->status !== 'completed')
                continue;
            $month = Carbon::createFromDate($task->completed_at)->format('M');
            if (!in_array($month, $months))
                $months[] = $month;
        }
        $data_map = ['months' => $months];
        foreach ($tasks as $task) {
            if ($task->status !== 'completed')
                continue;
            $role_name = $task->user->role->name;
            $month = Carbon::createFromDate($task->completed_at)->format('M');
            if (!array_key_exists($role_name, $data_map))
                $data_map[$role_name] = array_fill(0, count($months), 0);
            $idx = array_search($month, $months);
            $data_map[$role_name][$idx] += 1;
        }
        return $data_map;
    }

    public function getJournals(Request $request)
    {
        $user = $request->user();
        $transactions = Transaction::where('created_by', '=', $user->id)
            ->whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->where('type', '=', 'journal')
            ->orderBy('date')
            ->get();

        // fill up array of 0's, each index represents the month, (0 - 11)
        $data = array_fill(0, 12, 0);
        foreach ($transactions as $transaction) {
            // hence the minus 1
            $month_idx = Carbon::createFromDate($transaction->date)->month - 1;
            $data[$month_idx] += 1;
        }

        return Response::json([
            'values' => $data,
        ]);
    }
}
