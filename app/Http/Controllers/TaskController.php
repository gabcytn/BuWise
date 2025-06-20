<?php

namespace App\Http\Controllers;

use App\Jobs\TaskCreated;
use App\Models\Task;
use App\Models\User;
use App\Notifications\MarkedTaskAsComplete;
use Carbon\Carbon;
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
            return Task::where('assigned_to', '=', $user->id)
                ->orWhere('created_by', '=', $user->id)
                ->orderBy('start_date')
                ->get();
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
            'assigned_to' => 'required|uuid:4',
            'description' => 'required|string|max:255',
            'category' => 'required|in:invoice,journal,client,staff',
            'category_description' => 'required|in:manual_invoices,digital_invoices,manual_entry,csv_migration,create_client,update_client,suspend_client,delete_client,create_staff,update_staff,suspend_staff,delete_staff',
            'status' => 'required|in:not_started,in_progress,completed',
            'client' => 'nullable|uuid:4',
            'priority' => 'required|in:low,medium,high',
            'start_date' => [Rule::date()->format('Y-m-d')],
            'end_date' => [Rule::date()->format('Y-m-d')]
        ]);

        $assigned = User::find($request->assigned_to);
        $client = null;
        if ($request->client) {
            $client = User::find($request->client);
            if (!$client)
                return $this->backWithErrors('Client not found');
        }
        if (!$assigned)
            return $this->backWithErrors('Task assigned to is unknown');

        $task = Task::create([
            'name' => $request->name,
            'created_by' => $request->user()->id,
            'assigned_to' => $assigned->id,
            'client_id' => $client ? $client->id : null,
            'description' => $request->description,
            'category' => $request->category,
            'category_description' => $request->category_description,
            'status' => $request->status,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        Cache::forget($request->user()->id . '-tasks');
        TaskCreated::dispatch($task);

        // TODO: schedule task reminders

        return redirect()->back()->with('status', 'Created task successfully.');
    }

    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);
        $request->validate([
            'description' => 'required|string|max:255',
            'status' => 'required|in:not_started,in_progress,completed',
        ]);

        if ($task->status !== $request->status)
            $task->completed_at = Carbon::now()->format('Y-m-d');
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

    public function todo(Request $request)
    {
        Gate::authorize('viewAny', Task::class);
        $user = $request->user();
        $filters = $request->only(['client', 'staff', 'priority', 'search']);

        $tasks = Task::where('assigned_to', '=', $user->id)->orderBy('end_date');
        if (array_key_exists('client', $filters) && $filters['client'])
            $tasks->where('client_id', '=', $filters['client']);
        if (array_key_exists('priority', $filters) && $filters['priority'])
            $tasks->where('priority', '=', $filters['priority']);
        if (array_key_exists('search', $filters) && $filters['search'])
            $tasks->whereLike('name', '%' . $filters['search'] . '%');
        $tasks = $tasks->get();

        $completed = $tasks->where('status', '=', 'completed');
        $upcoming = $tasks->where('end_date', '>', Carbon::now()->endOfWeek()->format('Y-m-d'))->where('status', '!=', 'completed');
        $todo = $tasks->where('end_date', '<=', Carbon::now()->endOfWeek()->format('Y-m-d'))->where('status', '!=', 'completed');

        $accId = getAccountantId($user);
        $clients = Cache::remember("$accId-clients", 3600, function () use ($user) {
            return getClients($user);
        });

        return view('calendar.todo', [
            'todo' => $todo,
            'upcoming' => $upcoming,
            'completed' => $completed,
            'clients' => $clients,
        ]);
    }

    public function changeStatus(Request $request, Task $task)
    {
        Gate::authorize('update', $task);
        $request->validate([
            'status' => 'required|in:completed,not_started',
        ]);

        $task->status = $request->status;
        $task->completed_at = Carbon::now()->format('Y-m-d');
        $task->save();

        if ($request->status === 'completed') {
            $creator = $task->creator;
            $creator->notify(new MarkedTaskAsComplete($creator, $request->user()));
        }

        Cache::forget($request->user()->id . '-tasks');
        Cache::forget($task->created_by . '-tasks');
        return Response::json([
            'message' => 'Successfully updated status'
        ]);
    }

    public function assignedTasks(Request $request)
    {
        Gate::authorize('viewAny', Task::class);
        $user = $request->user();
        $tasks = Task::where('assigned_to', '=', $user->id)
            ->get();

        return Response::json([
            'tasks' => $tasks,
        ]);
    }

    private function backWithErrors(string $message)
    {
        return redirect()->back()->withErrors([
            'message' => $message,
        ]);
    }
}
