const deleteItemDialog = document.querySelector(".delete-item-dialog");
const closeDeleteItemDialogButton = document.querySelector(
    ".delete-item-dialog button[type='button']",
);
const deleteItemForm = document.querySelector(".delete-item-dialog form");
document.querySelectorAll(".action-column form").forEach((item) => {
    item.addEventListener("submit", (e) => {
        e.preventDefault();

        deleteItemForm.action = item.action;
        deleteItemDialog.showModal();
    });
});

closeDeleteItemDialogButton.addEventListener("click", () => {
    deleteItemForm.action = "#";
    deleteItemDialog.close();
});
