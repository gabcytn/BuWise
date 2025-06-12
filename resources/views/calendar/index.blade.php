<x-app-layout>
    @vite(['resources/js/calendar/index.js', 'resources/css/calendar/index.css'])
    <div class="container">
        <div class="content-wrapper">
            <div class="content calendar">
                <div id="calendar"></div>
            </div>
            <div class="content aside">
                <aside>
                    <ul>
                        <li>Hi</li>
                        <li>Hi</li>
                        <li>Hi</li>
                        <li>Hi</li>
                        <li>Hi</li>
                    </ul>
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
                        <input name="task_name" id="task-name" required />
                    </div>
                    <div class="task-input">
                        <label for="assign">Assign To</label>
                        <select name="assign" id="assign" required>
                            <option value="" selected disabled>Pick User to Assign</option>
                            <option value="{{ request()->user()->id }}">{{ request()->user()->name }}</option>
                            @foreach ($staff as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
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
                        <label for="frequency">Reminder Frequency</label>
                        <select name="frequency" id="frequency" required>
                            <option selected value="once">Once</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="annually">Annually</option>
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
            <form class="form" action="" method="POST">
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
            <button type="submit" form="add-task-form">Edit</button>
            <form action="" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
            <button type="button">Cancel</button>
        </div>
    </dialog>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
</x-app-layout>
