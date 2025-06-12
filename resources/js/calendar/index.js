const addTaskDialog = document.querySelector("dialog#add-task");
const viewTaskDialog = document.querySelector("dialog#view-task");
const startDate = document.querySelector("#start-date");
const endDate = document.querySelector("#end-date");
const taskForm = document.querySelector("#add-task-form");

const statusIdxMap = { not_started: 0, in_progress: 1, completed: 2 };
const bgColorMap = {
    not_started: "red",
    in_progress: "yellow",
    completed: "green",
};

async function getTasks() {
    try {
        const res = await fetch("/api/tasks", {
            headers: {
                Accept: "application/json",
            },
        });
        const data = await res.json();
        if (!res.ok) {
            throw new Error(`Error status code of ${res.status}`);
        }

        return data.tasks;
    } catch (e) {
        if (e instanceof Error) {
            console.error(e.message);
            alert("Request failed. Try again.");
        }
    }
}

start();

async function start() {
    const tasks = await getTasks();
    const calendarEl = document.getElementById("calendar");
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        selectable: true,
        selectMirror: true,
        dayMaxEventRows: 3,
        events: tasks.map((task) => {
            return {
                title: task.name,
                start: task.start_date,
                end: task.end_date,
                display:
                    getDateDifference(task.start_date, task.end_date) > 1
                        ? "block"
                        : "list-item",
                backgroundColor: bgColorMap[task.status],
                extendedProps: {
                    description: task.description,
                    status: task.status,
                    id: task.id,
                },
            };
        }),
        customButtons: {
            myCustomButton: {
                text: "Add Task",
                click: function () {
                    addTaskDialog.showModal();
                },
            },
        },
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,dayGridYear myCustomButton",
        },
        select: function (info) {
            const s = info.startStr;
            const e = info.endStr;
            startDate.value = s;
            endDate.value = e;
            addTaskDialog.showModal();
        },
        eventClick: function (e) {
            viewTaskDialog.querySelector("h3").textContent = e.event.title;
            viewTaskDialog.querySelector("#description").value =
                e.event.extendedProps.description;
            const s = e.event.extendedProps.status;
            viewTaskDialog.querySelector("#status")[statusIdxMap[s]].selected =
                true;
            const forms = viewTaskDialog.querySelectorAll("form");
            forms.forEach((form) => {
                form.action = `/tasks/${e.event.extendedProps.id}`;
            });
            viewTaskDialog.showModal();
        },
    });
    calendar.render();
    addEventListeners(calendar);
    displayChartOnSidebar(tasks);
    displayTasksOnSidebar(tasks);
}

function addEventListeners(calendar) {
    addTaskDialog
        .querySelector("button[type='button']")
        .addEventListener("click", () => {
            addTaskDialog.close();
        });
    viewTaskDialog
        .querySelector("button[type='button']")
        .addEventListener("click", () => {
            viewTaskDialog.close();
        });

    taskForm.addEventListener("submit", (e) => {
        e.preventDefault();
        calendar.addEvent({
            title: document.querySelector("#task-name").value,
            start: startDate.value,
            end: endDate.value,
            allDay: true,
            interactive: true,
            display:
                getDateDifference(startDate.value, endDate.value) > 1
                    ? "block"
                    : "list-item",
        });
        addTaskDialog.close();
        // taskForm.reset();

        postToServer();
    });
}
const taskName = document.querySelector("#task-name");
const assignSelect = document.querySelector("#assign");
const description = document.querySelector("#description");
const statusSelect = document.querySelector("#status");
const clientSelect = document.querySelector("#client");
const freqSelect = document.querySelector("#frequency");

async function postToServer() {
    try {
        const res = await fetch("/tasks", {
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                Accept: "application/json",
            },
            method: "POST",
            body: JSON.stringify({
                name: taskName.value,
                assignedTo: assignSelect[assignSelect.selectedIndex].value,
                description: description.value,
                status: statusSelect[statusSelect.selectedIndex].value,
                client: clientSelect[clientSelect.selectedIndex].value,
                frequency: freqSelect[freqSelect.selectedIndex].value,
                startDate: startDate.value,
                endDate: endDate.value,
            }),
        });
        const data = await res.json();
        if (!res.ok) throw new Error(`Error status code of ${res.status}`);
    } catch (e) {
        if (e instanceof Error) {
            console.error(e.message);
            alert("Error saving task.\nPlease try again.");
        }
    }
}

function displayTasksOnSidebar(tasks) {
    const ul = document.querySelector(".aside ul");
    tasks.forEach((task) => {
        const li = document.createElement("li");
        li.textContent = `${task.name}: ${task.start_date}`;
        ul.appendChild(li);
    });
}

function displayChartOnSidebar(tasks) {
    const data = {};
    tasks.forEach((task) => {
        let v = data[task.status];
        if (v) v++;
        else v = 1;
        data[task.status] = v;
    });
    const config = {
        type: "pie",
        data: {
            labels: Object.keys(data).map((datum) => datum.replace("_", " ")),
            datasets: [
                {
                    data: Object.values(data),
                },
            ],
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: "bottom",
                },
            },
        },
    };
    const tasksChart = document.querySelector("#chart");
    const myChart = new Chart(tasksChart, config);
}

function getDateDifference(d1, d2) {
    const date1 = new Date(d1);
    const date2 = new Date(d2);
    const diffTime = Math.abs(date2 - date1);
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
}
