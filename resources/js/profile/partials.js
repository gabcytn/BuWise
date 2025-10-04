// ---------- UPDATE PASSWORD ----------
const updatePasswordBtn = document.querySelector("button#update-password");
const updatePassDialog = document.querySelector("dialog.password-dialog");
const closeUpdatePass = updatePassDialog.querySelector("input[type='reset']");

closeUpdatePass.addEventListener("click", () => {
    updatePassDialog.close();
});
updatePasswordBtn.addEventListener("click", () => {
    updatePassDialog.showModal();
});

// ---------- DISABLE TWO-FACTOR AUTH  ----------
const disableMfaBtn = document.querySelector("button#disable-two-factor");
const disableMfaDialog = document.querySelector("dialog.mfa-dialog");
const closeMfaDialog = disableMfaDialog.querySelector("input[type='reset']");
const mfaButton = disableMfaDialog.querySelector("button[type='submit']");
const disableInput = document.querySelector("#disable");

disableMfaBtn.addEventListener("click", () => {
    disableMfaDialog.showModal();
});
closeMfaDialog.addEventListener("click", () => {
    mfaButton.classList.add("disabled");
    mfaButton.disabled = true;
    disableMfaDialog.close();
});
disableInput.addEventListener("input", () => {
    listener(disableInput, mfaButton, "disable");
});

// ---------- DELETE ACCOUNT ----------
const deleteAccBtn = document.querySelector("button#delete-account");
const deleteAccDialog = document.querySelector("dialog.delete-dialog");
const closeDeleteDialog = deleteAccDialog.querySelector("input[type='reset']");
const deleteButton = deleteAccDialog.querySelector("button[type='submit']");
const deleteInput = document.querySelector("#delete");

deleteAccBtn.addEventListener("click", () => {
    deleteAccDialog.showModal();
});
closeDeleteDialog.addEventListener("click", () => {
    deleteButton.classList.add("disabled");
    deleteButton.disabled = true;
    deleteAccDialog.close();
});
deleteInput.addEventListener("input", () => {
    listener(deleteInput, deleteButton, "delete");
});

// ---------- UPDATE DEFAULT NEW USERS PASSWORD ----------
const updateDefaultBtn = document.querySelector("button#change-default-btn");
const defaultPasswordDialog = document.querySelector(
    "dialog.default-password-dialog",
);
const closeDefaultPasswordDialog = defaultPasswordDialog.querySelector(
    "input[type='reset']",
);
document.querySelector("#new-default").addEventListener("input", (e) => {
    const val = e.target.value;
    const submitBtn = defaultPasswordDialog.querySelector(
        "button[type='submit']",
    );
    if (val.length < 8) {
        submitBtn.disabled = true;
    } else {
        submitBtn.disabled = false;
    }
});
updateDefaultBtn.addEventListener("click", () => {
    defaultPasswordDialog.showModal();
});
closeDefaultPasswordDialog.addEventListener("click", () => {
    defaultPasswordDialog.close();
});

// ---------- HELPER FUNCTION ----------
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

// ---------- NOTIFICATIONS GRANTING ----------
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
