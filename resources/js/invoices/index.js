const dropdownBtn = document.querySelector("button.dropdown-toggle");
const menu = document.querySelector(".dropdown-menu");
const dialog = document.querySelector("#scan-invoice-dialog");
dropdownBtn.addEventListener("click", () => {
    menu.classList.toggle("d-block");
});

document.querySelector("#from-gallery").addEventListener("click", () => {
    dialog.showModal();
});

dialog.querySelector("button[type='button']").addEventListener("click", () => {
    dialog.close();
});
