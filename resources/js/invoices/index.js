// item deletion confirmation logic
const confirmableDialog = document.querySelector("dialog.confirmable-dialog");

let formToSubmit = null;
document.querySelectorAll(".delete-item-form").forEach((form) => {
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const referenceNo =
            form.parentNode.parentNode.parentNode.children[0].textContent;
        confirmableDialog.querySelector("h4").textContent =
            `Delete invoice # ${referenceNo}?`;
        formToSubmit = form;
        confirmableDialog.showModal();
    });
});

confirmableDialog
    .querySelector("button.affirm-button")
    .addEventListener("click", () => {
        formToSubmit.submit();
    });

confirmableDialog
    .querySelector("button.deny-button")
    .addEventListener("click", () => {
        formToSubmit = null;
        confirmableDialog.close();
    });

// redirect to view the invoice details
document.querySelectorAll(".table-management tbody tr").forEach((row) => {
    const itemId = row.dataset.itemId;
    row.addEventListener("click", (e) => {
        if (e.target.classList.contains("fa-trash-can")) {
            return;
        }
        window.location.href = `/invoices/${itemId}`;
    });
});
