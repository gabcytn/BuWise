const addTaskDialog = document.querySelector("dialog#add-task");
const startDate = document.querySelector("#start-date");
const endDate = document.querySelector("#end-date");
const taskForm = document.querySelector("#add-task-form");

let globalCalendar = null;
var calendarEl = document.getElementById("calendar");
var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    selectable: true,
    selectMirror: true,
    dayMaxEventRows: 3,
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
        globalCalendar = calendar;
        addTaskDialog.showModal();
    },
    eventClick: function (e) {
        alert(`Remove ${e.event.title}?`);
        console.log(e.event.remove());
    },
});
calendar.render();

addTaskDialog
    .querySelector("button[type='button']")
    .addEventListener("click", () => {
        addTaskDialog.close();
    });

taskForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const date1 = new Date(startDate.value);
    const date2 = new Date(endDate.value);
    const diffTime = Math.abs(date2 - date1);
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    calendar.addEvent({
        title: document.querySelector("#task-name").value,
        start: startDate.value,
        end: endDate.value,
        allDay: true,
        interactive: true,
        display: diffDays > 1 ? "block" : "list-item",
    });
    addTaskDialog.close();
    taskForm.reset();

    postToServer();
});

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
        if (!res.ok) throw new Error(`Error status code of ${res.status}`);
    } catch (e) {
        if (e instanceof Error) {
            console.error(e.message);
            alert("Error saving task.\nPlease try again.");
        }
    }
}
