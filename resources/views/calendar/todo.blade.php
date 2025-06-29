<x-app-layout title="Todo">
    @vite(['resources/css/calendar/todo.css', 'resources/js/calendar/todo.js'])
    <div class="container">
        <div class="page-header">
            <h1>Today's Progress</h1>
            <p>{{ \Carbon\Carbon::now()->format('d M Y') . ' | ' . request()->user()->role->name }}</p>
        </div>
        <div class="charts-row">
            <div class="chart-container">
                <canvas id="invoice-chart"></canvas>
                <div class="chart-label">
                    <h2></h2>
                    <h1></h1>
                    <p></p>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="journal-chart"></canvas>
                <div class="chart-label">
                    <h2></h2>
                    <h1></h1>
                    <p></p>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="client-chart"></canvas>
                <div class="chart-label">
                    <h2></h2>
                    <h1></h1>
                    <p></p>
                </div>
            </div>
            @if (in_array(request()->user()->role_id, [\App\Models\Role::ACCOUNTANT, \App\Models\Role::LIAISON]))
                <div class="chart-container">
                    <canvas id="staff-chart"></canvas>
                    <div class="chart-label">
                        <h2></h2>
                        <h1></h1>
                        <p></p>
                    </div>
                </div>
            @endif
        </div>
        <div class="todo-body">
            <form class="filters-row">
                <div class="search-container">
                    <input type="search" id="search" name="search" placeholder="Search Tasks"
                        value="{{ request()->query('search') }}" />
                </div>
                <div class="select-container">
                    <select name="client">
                        <option value="" selected>All Clients</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}"
                                {{ request()->query('client') === $client->id ? 'selected' : '' }}>{{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="select-container">
                    <select name="priority">
                        <option value="" selected>All Priority</option>
                        <option {{ request()->query('priority') === 'high' ? 'selected' : '' }} value="high">High
                        </option>
                        <option {{ request()->query('priority') === 'medium' ? 'selected' : '' }} value="medium">Medium
                        </option>
                        <option {{ request()->query('priority') === 'low' ? 'selected' : '' }} value="low">Low
                        </option>
                    </select>
                </div>
            </form>
            <section class="todo-table content">
                <div class="table-wrapper">
                    <table>
                        <caption>To Do</caption>
                        <thead>
                            <tr>
                                <th>Task Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Category Description</th>
                                <th>Client</th>
                                <th>Priority</th>
                                <th>Due Date</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($todo as $item)
                                <tr data-task-id="{{ $item->id }}" data-task-complete="false">
                                    <td><input type="checkbox" />{{ $item->name }}</td>
                                    <td title="{{ $item->description }}">{{ truncate($item->description) }}</td>
                                    <td>{{ ucfirst($item->category) }}</td>
                                    <td>{{ \Illuminate\Support\Str::of($item->category_description)->replace('_', ' ')->title() }}
                                    </td>
                                    <td>{{ $item->toClient?->name }}</td>
                                    <td>{{ ucfirst($item->priority) }}</td>
                                    <td>{{ $item->end_date }}</td>
                                    <td>{{ $item->creator->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="todo-table upcoming">
                <div class="table-wrapper">
                    <table>
                        <caption>Upcoming</caption>
                        <thead>
                            <tr>
                                <th>Task Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Category Description</th>
                                <th>Client</th>
                                <th>Priority</th>
                                <th>Due Date</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($upcoming as $item)
                                <tr data-task-id="{{ $item->id }}" data-task-complete="false">
                                    <td>{{ $item->name }}</td>
                                    <td title="{{ $item->description }}">{{ truncate($item->description) }}</td>
                                    <td>{{ ucfirst($item->category) }}</td>
                                    <td>{{ \Illuminate\Support\Str::of($item->category_description)->replace('_', ' ')->title() }}
                                    </td>
                                    <td>{{ $item->toClient?->name }}</td>
                                    <td>{{ ucfirst($item->priority) }}</td>
                                    <td>{{ $item->end_date }}</td>
                                    <td>{{ $item->creator->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="todo-table completed">
                <div class="table-wrapper">
                    <table>
                        <caption>Completed</caption>
                        <thead>
                            <tr>
                                <th>Task Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Category Description</th>
                                <th>Client</th>
                                <th>Priority</th>
                                <th>Completed At</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($completed as $item)
                                <tr data-task-id="{{ $item->id }}" data-task-complete="true">
                                    <td>{{ $item->name }}</td>
                                    <td title="{{ $item->description }}">{{ truncate($item->description) }}</td>
                                    <td>{{ ucfirst($item->category) }}</td>
                                    <td>{{ \Illuminate\Support\Str::of($item->category_description)->replace('_', ' ')->title() }}
                                    </td>
                                    <td>{{ $item->toClient?->name }}</td>
                                    <td>{{ ucfirst($item->priority) }}</td>
                                    <td>{{ $item->completed_at }}</td>
                                    <td>{{ $item->creator->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</x-app-layout>
