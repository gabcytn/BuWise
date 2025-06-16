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

document.querySelectorAll("table td button").forEach((button) => {
    button.addEventListener("click", async () => {
        const id = button.parentElement.parentElement.dataset.taskId;
        const isComplete =
            button.parentElement.parentElement.dataset.taskComplete === "true";
        fetch(`/tasks/status/${id}`, {
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
    });
});
