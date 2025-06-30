const deleteItemDialog = document.querySelector(".delete-item-dialog");
const closeDeleteItemDialogButton = document.querySelector(
    ".delete-item-dialog button[type='button']",
);
const deleteItemForm = document.querySelector(".delete-item-dialog form");
document.querySelectorAll("#delete-form").forEach((item) => {
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

// const dialog = document.querySelectorAll("dialog");
// outsideDialogClicked(dialog);
//
// function outsideDialogClicked(dialogs) {
//     if (dialog.length < 1) return;
//     dialogs.forEach((dialog) => {
//         dialog.addEventListener("click", (e) => {
//             const dialogDimensions = dialog.getBoundingClientRect();
//             if (
//                 e.clientX < dialogDimensions.left ||
//                 e.clientX > dialogDimensions.right ||
//                 e.clientY < dialogDimensions.top ||
//                 e.clientY > dialogDimensions.bottom
//             ) {
//                 dialog.close();
//             }
//         });
//     });
// }
