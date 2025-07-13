const updatePasswordBtn = document.querySelector("button#update-password");
const disableMfaBtn = document.querySelector("button#disable-two-factor");
const deleteAccBtn = document.querySelector("button#delete-account");

const updatePassDialog = document.querySelector("dialog.password-dialog");
const disableMfaDialog = document.querySelector("dialog.mfa-dialog");
const deleteAccDialog = document.querySelector("dialog.delete-dialog");

const closeUpdatePass = updatePassDialog.querySelector("input[type='reset']");
const closeMfaDialog = disableMfaDialog.querySelector("input[type='reset']");
const closeDeleteDialog = deleteAccDialog.querySelector("input[type='reset']");

updatePasswordBtn.addEventListener("click", () => {
    updatePassDialog.showModal();
});

closeUpdatePass.addEventListener("click", () => {
    updatePassDialog.close();
});

const mfaButton = disableMfaDialog.querySelector("button[type='submit']");
const disableInput = document.querySelector("#disable");
disableMfaBtn.addEventListener("click", () => {
    listener(disableInput, mfaButton, "disable");
    disableMfaDialog.showModal();
});

closeMfaDialog.addEventListener("click", () => {
    disableMfaDialog.close();
});

const deleteButton = deleteAccDialog.querySelector("button[type='submit']");
const deleteInput = document.querySelector("#delete");
deleteAccBtn.addEventListener("click", () => {
    listener(deleteInput, deleteButton, "delete");
    deleteAccDialog.showModal();
});

closeDeleteDialog.addEventListener("click", () => {
    deleteAccDialog.close();
});

disableInput.addEventListener("input", () => {
    listener(disableInput, mfaButton, "disable");
});

deleteInput.addEventListener("input", () => {
    listener(deleteInput, deleteButton, "delete");
});

function listener(input, button, valueToType) {
    const v = input.value;
    if (v === valueToType) {
        button.classList.remove("disabled");
        button.disabled = false;
    } else {
        button.classList.add("disabled");
        button.disabled = true;
    }
}

// notifications

document.querySelector("#notif-btn").addEventListener("click", () => {
    if (!("Notification" in window)) {
        alert("Your browser does not support notifications.");
    } else if (Notification.permission === "granted") {
        alert("Notifications are already granted.");
    } else if (Notification.permission === "denied") {
        alert(
            "Notifications are blocked. Please enable them in your browser settings.",
        );
    } else {
        Notification.requestPermission();
    }
});
