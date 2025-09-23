@props(['tasks'])
@vite(['resources/css/components/dashboard/todo-list.css'])
<div class="chart-card grid-child-4">
    <div class="chart-header">
        <h3>To Do List</h3>
    </div>
    @if (count($tasks) > 0)
        <ul class="tasks-list">
            @foreach ($tasks as $item)
                <li class="task-item">
                    <div class="task-content">
                        <div class="task-title">{{ $item->name }}</div>
                        <div class="task-meta">Due:
                            {{ \Carbon\Carbon::createFromDate($item->end_date)->format('M d Y') }}</div>
                        <div class="task-assigned">Created by {{ $item->creator->name }}</div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="no-tasks-container">
            <i class="fa-solid fa-ban"></i>
            <h1>No tasks yet</h1>
            @if (request()->user()->role_id === \App\Models\Role::ACCOUNTANT)
                <form action="/tasks">
                    <button type="submit">Add New Task</button>
                </form>
            @endif
        </div>
    @endif
</div>
