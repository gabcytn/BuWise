<x-app-layout title="Calendar">
    @vite(['resources/js/calendar/index.js', 'resources/css/calendar/index.css'])
    <div class="container">
        <div class="content-wrapper">
            <div class="content calendar">
                <div id="calendar"></div>
            </div>
            <div class="aside">
                <aside>
                    <div class="content chart-container">
                        <h4>Task Statistics</h4>
                        <canvas id="chart"></canvas>
                    </div>
                    <div class="content task-list">
                        <ul>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
    <dialog id="add-task">
        <h2>Add Task</h2>
        <div class="form-wrapper">
            <form action="" method="POST" id="add-task-form">
                @csrf
                <div class="form-left">
                    <div class="task-input">
                        <label for="task-name">Task Name</label>
                        <input name="name" id="task-name" required />
                    </div>
                    <div class="task-input">
                        <label for="assign">Assign To</label>
                        <select name="assigned_to" id="assign" required>
                            <option value="" selected disabled>Pick User to Assign</option>
                            <option value="{{ request()->user()->id }}">{{ request()->user()->name }}</option>
                            @foreach ($staff as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="task-input row">
                        <div class="col">
                            <label for="category">Category</label>
                            <select name="category" id="category" required>
                                <option value="" selected disabled>Select Category</option>
                                <option value="invoice">Invoice</option>
                                <option value="journal">Journal Entry</option>
                                <option value="client">Client Management</option>
                                <option value="staff">Staff Management</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="type">Type</label>
                            <select name="category_description" id="type" required>
                                <option value="" selected disabled>Select Task Type</option>
                                <option class="d-none invoice-option" value="manual_invoices">Manual Invoices</option>
                                <option class="d-none invoice-option" value="digital_invoices">Digital Invoices</option>
                                <option class="d-none journal-option" value="manual_entry">Manual Entry</option>
                                <option class="d-none journal-option" value="csv_migration">CSV Migration</option>
                                <option class="d-none client-option" value="create_client">Create Client Account
                                </option>
                                <option class="d-none client-option" value="update_client">Update Client Account
                                </option>
                                <option class="d-none client-option" value="suspend_client">Suspend Client Account
                                </option>
                                <option class="d-none client-option" value="delete_client">Delete Client Account
                                </option>
                                <option class="d-none staff-option" value="create_staff">Create Staff Account</option>
                                <option class="d-none staff-option" value="update_staff">Update Staff Account</option>
                                <option class="d-none staff-option" value="suspend_staff">Suspend Staff Account</option>
                                <option class="d-none staff-option" value="delete_staff">Delete Staff Account</option>
                            </select>
                        </div>
                    </div>
                    <div class="task-input">
                        <label for="description">Description</label>
                        <textarea rows="7" name="description" id="description" required></textarea>
                    </div>
                </div>
                <div class="form-right">
                    <div class="task-input">
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option selected value="not_started">Not Started</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="task-input">
                        <label for="client">Client (optional)</label>
                        <select name="client" id="client">
                            <option value="" selected disabled>Pick Category</option>
                            <option value="none">None</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="task-input">
                        <label for="priority">Priority</label>
                        <select name="priority" id="priority" required>
                            <option value="low">Low</option>
                            <option selected value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="task-input">
                        <label for="start-date">Start Date</label>
                        <input type="date" name="start_date" id="start-date" required />
                    </div>
                    <div class="task-input">
                        <label for="end-date">End Date</label>
                        <input type="date" name="end_date" id="end-date" required />
                    </div>
                </div>
            </form>
        </div>
        <hr />
        <div class="form-wrapper">
            <button type="submit" form="add-task-form">Create</button>
            <button type="button">Cancel</button>
        </div>
    </dialog>
    <dialog id="view-task">
        <h3>Task Name Here</h3>
        <div class="form-wrapper">
            <form id="edit-task-form" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="task-input">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="6"></textarea>
                </div>
                <div class="task-input">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="not_started">Not Started</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </form>
        </div>
        <hr />
        <div class="form-wrapper button-container">
            <button type="submit" form="edit-task-form">Edit</button>
            @if (request()->user()->role_id === \App\Models\Role::ACCOUNTANT)
                <form action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            @endif
            <button type="button">Cancel</button>
        </div>
    </dialog>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</x-app-layout>
