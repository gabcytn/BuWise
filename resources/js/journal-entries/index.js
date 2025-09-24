const tr = document.querySelectorAll(".journal-row");
const deleteEntryButtons = document.querySelectorAll(".action-column button");

const confirmableDialog = document.querySelector(".confirmable-dialog");
const confirmableDialogAffirmButton = document.querySelector(".affirm-button");
const confirmableDialogDenyButton = document.querySelector(".deny-button");

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
            location = window.origin + "/" + row.dataset.url;
        }
    });
});
