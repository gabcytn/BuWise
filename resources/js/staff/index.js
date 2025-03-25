const addStaffDialog = document.querySelector("#add-staff-dialog");

document.querySelector("#open-dialog-btn").addEventListener("click", () => {
    addStaffDialog.showModal();
});

document.querySelector("#close-dialog-btn").addEventListener("click", () => {
    addStaffDialog.close();
});
