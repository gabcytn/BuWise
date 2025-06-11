<?php

namespace App\Http\Controllers;

use App\Events\NotificationReminders;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class TaskController extends Controller
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

    public function store(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            Log::info("$key: $value");
        }
        $request->validate([
            'name' => 'required|string|max:150',
            'assignedTo' => 'required|uuid:4',
            'description' => 'required|string|max:255',
            'status' => 'required|in:not_started,in_progress,completed',
            'client' => 'nullable|uuid:4',
            'frequency' => 'required|in:once,daily,weekly,monthly,quarterly,annually',
            'startDate' => [Rule::date()->format('Y-m-d')],
            'endDate' => [Rule::date()->format('Y-m-d')]
        ]);

        $assigned = User::find($request->assignedTo);
        $client = null;
        if (!$assigned)
            return $this->backWithErrors('Task assigned to is unknown');
        if ($request->client) {
            $client = User::find($request->client);
            if (!$client)
                return $this->backWithErrors('Client not found');
        }

        Task::create([
            'name' => $request->name,
            'assigned_to' => $assigned->id,
            'client' => $client ? $client->id : null,
            'description' => $request->description,
            'status' => $request->status,
            'frequency' => $request->frequency,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
        ]);

        // TODO: schedule task reminders

        return Response::json([
            'message' => 'Successfully created task'
        ], 201);
    }

    private function backWithErrors(string $message)
    {
        return Response::json([
            'message' => $message,
        ]);
    }
}
