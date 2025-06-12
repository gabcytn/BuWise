<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Task::class);
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

    public function tasks(Request $request)
    {
        Gate::authorize('viewAny', Task::class);
        $user = $request->user();
        $tasks = Cache::remember($user->id . '-tasks', 3600, function () use ($user) {
            return Task::with(['user', 'toClient'])->where('assigned_to', '=', $user->id)->get();
        });
        return Response::json([
            'tasks' => $tasks,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Task::class);
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

        Cache::forget($assigned->id . '-tasks');

        // TODO: schedule task reminders

        return Response::json([
            'message' => 'Successfully created task'
        ], 201);
    }

    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);
        $request->validate([
            'description' => 'required|string|max:255',
            'status' => 'required|in:not_started,in_progress,completed',
        ]);

        $task->description = $request->description;
        $task->status = $request->status;
        $task->save();

        Cache::forget($request->user()->id . '-tasks');

        return redirect()->back()->with('status', 'Successfully updated task');
    }

    public function destroy(Request $request, Task $task)
    {
        Gate::authorize('delete', $task);
        $task->delete();
        Cache::forget($request->user()->id . '-tasks');
        return redirect()->back()->with('status', 'Successfully deleted task');
    }

    private function backWithErrors(string $message)
    {
        return Response::json([
            'message' => $message,
        ]);
    }
}
