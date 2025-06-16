<x-app-layout>
    @vite(['resources/css/calendar/todo.css', 'resources/js/calendar/todo.js'])
    <div class="container">
        <div class="page-header">
            <h1>Today's Progress</h1>
            <p>{{ \Carbon\Carbon::now()->format('d M Y') . ' | ' . request()->user()->role->name }}</p>
        </div>
        <div class="charts-row">
            <div class="chart-container">
                <canvas id=""></canvas>
                <div class="chart-label"></div>
            </div>
            <div class="chart-container">
                <canvas id=""></canvas>
                <div class="chart-label"></div>
            </div>
            <div class="chart-container">
                <canvas id=""></canvas>
                <div class="chart-label"></div>
            </div>
            <div class="chart-container">
                <canvas id=""></canvas>
                <div class="chart-label"></div>
            </div>
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
                @if (request()->user()->role_id === \App\Models\Role::ACCOUNTANT)
                    <div class="select-container">
                        <select name="staff">
                            <option value="" selected>All Staff</option>
                            @foreach ($staffs as $staff)
                                <option value="{{ $client->id }}"
                                    {{ request()->query('staff') === $staff->id ? 'selected' : '' }}>{{ $staff->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
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
                <h3>To Do</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Assigned To</th>
                            <th>Client</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($todo as $item)
                            <tr data-task-id="{{ $item->id }}" data-task-complete="false">
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->toClient?->name }}</td>
                                <td>{{ ucfirst($item->priority) }}</td>
                                <td>{{ $item->end_date }}</td>
                                <td>{{ $item->creator->name }}</td>
                                <td><button>Mark as Complete</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            <section class="todo-table upcoming">
                <h3>Upcoming</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Assigned To</th>
                            <th>Client</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($upcoming as $item)
                            <tr data-task-id="{{ $item->id }}" data-task-complete="false">
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->toClient?->name }}</td>
                                <td>{{ ucfirst($item->priority) }}</td>
                                <td>{{ $item->end_date }}</td>
                                <td>{{ $item->creator->name }}</td>
                                <td><button>Mark as Complete</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            <section class="todo-table completed">
                <h3>Completed</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Assigned To</th>
                            <th>Client</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($completed as $item)
                            <tr data-task-id="{{ $item->id }}" data-task-complete="true">
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->toClient?->name }}</td>
                                <td>{{ ucfirst($item->priority) }}</td>
                                <td>{{ $item->end_date }}</td>
                                <td>{{ $item->creator->name }}</td>
                                <td><button>Mark Incomplete</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</x-app-layout>
