const btn = document.querySelector("#set-initial-balance-btn");
const cancelBtn = document.querySelector("#set-initial-balance-dialog button[type='button']");
const dialog = document.querySelector("#set-initial-balance-dialog");

btn.addEventListener("click", () => {
    dialog.showModal();
})

cancelBtn.addEventListener("click", () => {
    dialog.close();
})
