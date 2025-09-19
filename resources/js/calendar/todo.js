const form = document.querySelector("form.filters-row");
const clientSelect = document.querySelector("select[name='client']");
const staffSelect = document.querySelector("select[name='staff']");
const prioritySelect = document.querySelector("select[name='priority']");

prioritySelect.addEventListener("change", submitForm);
clientSelect.addEventListener("change", submitForm);
if (staffSelect) staffSelect.addEventListener("change", submitForm);

function submitForm() {
    form.submit();
}

const completed = document.querySelector(".todo-table.completed tbody");
const upcoming = document.querySelector(".todo-table.upcoming tbody");
const current = document.querySelector(".todo-table.content tbody");
document.querySelectorAll("input[type='checkbox']").forEach((button) => {
    button.addEventListener("change", async () => {
        const id = button.parentElement.parentElement.dataset.taskId;
        const isComplete =
            button.parentElement.parentElement.dataset.taskComplete === "true";
        await fetch(`/tasks/status/${id}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify({
                status: isComplete ? "not_started" : "completed",
            }),
        });
        appendToAppropriateTable(button);
    });
});

function appendToAppropriateTable(button) {
    const isComplete =
        button.parentElement.parentElement.dataset.taskComplete === "true";
    const row = button.parentElement.parentElement;
    row.remove();
    if (isComplete) {
        current.appendChild(row);
        row.dataset.taskComplete = "false";
    } else {
        completed.appendChild(row);
        row.dataset.taskComplete = "true";
    }
}

fetchTasks();
async function fetchTasks() {
    const res = await fetch("/api/tasks");
    const data = await res.json();
    const tasks = data.tasks;
    const allJournals = tasks.filter((task) => task.category === "journal");
    const allInvoices = tasks.filter((task) => task.category === "invoice");
    const allClients = tasks.filter((task) => task.category === "client");
    const allStaffs = tasks.filter((task) => task.category === "staff");
    const processedJournals = allJournals.filter(
        (journal) => journal.status === "completed",
    );
    const processedInvoices = allInvoices.filter(
        (invoice) => invoice.status === "completed",
    );
    const processedClients = allClients.filter(
        (client) => client.status === "completed",
    );
    const processedStaffs = allStaffs.filter(
        (staff) => staff.status === "completed",
    );
    populateInvoiceCharts(
        processedInvoices.length,
        allInvoices.length - processedInvoices.length,
    );
    populateJournalCharts(
        processedJournals.length,
        allJournals.length - processedJournals.length,
    );
    populateClientCharts(
        processedClients.length,
        allClients.length - processedClients.length,
    );
    populateStaffCharts(
        processedStaffs.length,
        allStaffs.length - processedStaffs.length,
    );
}

const invoiceChart = document.querySelector("canvas#invoice-chart");
const journalChart = document.querySelector("canvas#journal-chart");
const clientChart = document.querySelector("canvas#client-chart");
const staffChart = document.querySelector("canvas#staff-chart");

function populateInvoiceCharts(invoicesLength, remainingLength) {
    const data = {
        labels: ["Complete", "Remaining"],
        datasets: [
            {
                data: [invoicesLength, remainingLength],
                backgroundColor: ["#1B80C3", "rgba(0, 0, 0, 0.2)"],
            },
        ],
    };
    const config = {
        type: "doughnut",
        data: data,
    };

    const myChart = new Chart(invoiceChart, config);

    const title = invoiceChart.nextElementSibling.querySelector("h2");
    const amount = invoiceChart.nextElementSibling.querySelector("h1");
    const subtitle = invoiceChart.nextElementSibling.querySelector("p");
    updateChartLabels(
        title,
        amount,
        subtitle,
        "Invoice",
        invoicesLength,
        remainingLength,
        invoicesLength + remainingLength,
    );
}

function populateJournalCharts(journalsLength, remainingLength) {
    const data = {
        labels: ["Complete", "Remaining"],
        datasets: [
            {
                data: [journalsLength, remainingLength],
                backgroundColor: ["#1B80C3", "rgba(0, 0, 0, 0.2)"],
            },
        ],
    };
    const config = {
        type: "doughnut",
        data: data,
    };

    const myChart = new Chart(journalChart, config);

    const title = journalChart.nextElementSibling.querySelector("h2");
    const amount = journalChart.nextElementSibling.querySelector("h1");
    const subtitle = journalChart.nextElementSibling.querySelector("p");
    updateChartLabels(
        title,
        amount,
        subtitle,
        "Journal",
        journalsLength,
        remainingLength,
        journalsLength + remainingLength,
    );
}

function populateClientCharts(clientsLength, remainingLength) {
    const data = {
        labels: ["Complete", "Remaining"],
        datasets: [
            {
                data: [clientsLength, remainingLength],
                backgroundColor: ["#1B80C3", "rgba(0, 0, 0, 0.2)"],
            },
        ],
    };
    const config = {
        type: "doughnut",
        data: data,
    };

    const myChart = new Chart(clientChart, config);

    const title = clientChart.nextElementSibling.querySelector("h2");
    const amount = clientChart.nextElementSibling.querySelector("h1");
    const subtitle = clientChart.nextElementSibling.querySelector("p");
    updateChartLabels(
        title,
        amount,
        subtitle,
        "Client",
        clientsLength,
        remainingLength,
        clientsLength + remainingLength,
    );
}

function populateStaffCharts(staffLength, remainingLength) {
    const data = {
        labels: ["Complete", "Remaining"],
        datasets: [
            {
                data: [staffLength, remainingLength],
                backgroundColor: ["#1B80C3", "rgba(0, 0, 0, 0.2)"],
            },
        ],
    };
    const config = {
        type: "doughnut",
        data: data,
    };

    const myChart = new Chart(staffChart, config);

    const title = staffChart.nextElementSibling.querySelector("h2");
    const amount = staffChart.nextElementSibling.querySelector("h1");
    const subtitle = staffChart.nextElementSibling.querySelector("p");
    updateChartLabels(
        title,
        amount,
        subtitle,
        "Staff",
        staffLength,
        remainingLength,
        staffLength + remainingLength,
    );
}

function updateChartLabels(
    title,
    amount,
    subtitle,
    titleContent,
    amountContent,
    remainingLength,
    totalLength,
) {
    title.textContent = `Total ${titleContent} Processed`;
    amount.textContent = amountContent;
    amount.style.color = "#1B80C3";
    subtitle.textContent = `${remainingLength} out of ${totalLength} remaining`;
}

// make font color red if the due date is overdue
document.querySelectorAll(".todo-table.content tbody tr").forEach((item) => {
    const date = new Date(item.querySelector("td:nth-child(7)").textContent);
    if (date < new Date()) {
        item.querySelectorAll("td").forEach((i) => (i.style.color = "red"));
    }
});

// transform dates to readable format
document.querySelectorAll("td:nth-child(7)").forEach((item) => {
    const textContent = item.textContent;
    const dateOptions = { month: "short", year: "numeric", day: "2-digit" };
    const date = new Date(textContent).toLocaleDateString("en-US", dateOptions);
    item.textContent = date;
});
