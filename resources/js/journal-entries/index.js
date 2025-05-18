const tr = document.querySelectorAll(".journal-row");
const deleteEntryButtons = document.querySelectorAll(".action-column button");

const confirmableDialog = document.querySelector(".confirmable-dialog");
const confirmableDialogAffirmButton = document.querySelector(".affirm-button");
const confirmableDialogDenyButton = document.querySelector(".deny-button");
const selectType = document.querySelector(".select.select-type");
const selectInvoice = document.querySelector(".select.select-invoice");

displaySelect();

confirmableDialogDenyButton.addEventListener("click", () => {
    confirmableDialog.close();
});

deleteEntryButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
        e.preventDefault();

        confirmableDialogAffirmButton.setAttribute(
            "form",
            button.parentElement.id,
        );
        confirmableDialog.showModal();
    });
});

tr.forEach((row) => {
    row.addEventListener("click", (e) => {
        if (!e.target.classList.contains("fa-regular")) {
            location = row.dataset.url;
        }
    });
});

selectType.addEventListener("change", () => {
    displaySelect();
});

function displaySelect() {
    const selectedType = selectType.options[selectType.selectedIndex].value;
    if (selectedType === "invoices") {
        selectInvoice.disabled = false;
        selectInvoice.required = true;
        selectInvoice.classList.remove("d-none");
    } else {
        selectInvoice.disabled = true;
        selectInvoice.required = false;
        selectInvoice.classList.add("d-none");
    }
}

const selectClients = document.querySelector(".select-clients");

selectClients.addEventListener("change", () => {
    const selectedClient =
        selectClients.options[selectClients.selectedIndex].value;

    if (selectedClient === "all") {
        // location.href = location.origin + location.pathname;
        return;
    }
    // location.href =
    // location.origin + location.pathname + `?filter=${selectedClient}`;
});
