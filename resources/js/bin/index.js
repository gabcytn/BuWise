const checkboxes = document.querySelectorAll("input[type='checkbox']");
const restoreButton = document.querySelector("#restore-btn");
const deleteButton = document.querySelector("#delete-btn");
const confirmDeleteDialog = document.querySelector("dialog#confirm-delete");
const confirmRestoreDialog = document.querySelector("dialog#confirm-restore");
const confirmDeleteButton = confirmDeleteDialog.querySelector(
    "button[type='submit']",
);
const confirmRestoreButton = confirmRestoreDialog.querySelector(
    "button[type='submit']",
);

checkboxesIsChecked();
checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", () => {
        checkboxesIsChecked();
    });
});

function checkboxesIsChecked() {
    let isDisabled = true;
    checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            isDisabled = false;
            return;
        }
    });
    toggleDisabledOnActionButtons(isDisabled);
}

function toggleDisabledOnActionButtons(isDisabled) {
    restoreButton.disabled = isDisabled;
    deleteButton.disabled = isDisabled;
}

restoreButton.addEventListener("click", () => {
    if (restoreButton.disabled) return;
    confirmRestoreDialog.showModal();
});

deleteButton.addEventListener("click", () => {
    if (deleteButton.disabled) return;
    confirmDeleteDialog.showModal();
});

// close dialog buttons
document
    .querySelectorAll("dialog .buttons-row button[type='button']")
    .forEach((button) => {
        button.addEventListener("click", () => {
            button.closest("dialog").close();
        });
    });

confirmRestoreButton.addEventListener("click", () => {
    submitForm("restore");
    confirmRestoreButton.closest("dialog").close();
});
confirmDeleteButton.addEventListener("click", () => {
    submitForm("delete");
    confirmDeleteButton.closest("dialog").close();
});

async function submitForm(action) {
    if (!["delete", "restore"].includes(action)) {
        alert("Invalid action.");
        return;
    }

    const items = [];
    checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            const tr = checkbox.closest("tr");
            items.push(tr.dataset.id);
            tr.remove();
        }
    });

    const res = await fetch(`/bin/${action}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']")
                .content,
        },
        body: JSON.stringify({
            items: items,
        }),
    });
}
