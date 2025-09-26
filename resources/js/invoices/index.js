const dialog = document.querySelector("#scan-invoice-dialog");

document.querySelector("#import-btn").addEventListener("click", () => {
    dialog.showModal();
});

dialog.querySelector("button[type='button']").addEventListener("click", () => {
    dialog.close();
});
