const hamburger = document.querySelector(".header-sm .fa-bars");
const nav = document.querySelector(".nav-sm");
hamburger.addEventListener("click", (e) => {
    hamburger.classList.toggle("hidden");
    nav.classList.toggle("hidden");
});

const popover = document.querySelector(".popover");

document
    .querySelector(".header-side__account")
    .addEventListener("click", (e) => {
        popover.classList.toggle("d-none");
    });

document.getElementById("profile").addEventListener("click", () => {
    window.location.href = "/profile";
});

const confirmLogoutDialog = document.querySelector(".confirm-logout-dialog");
document.getElementById("logout").addEventListener("click", () => {
    confirmLogoutDialog.showModal();
});

const cancelLogoutDialogButton = document.querySelector(
    ".confirm-logout-dialog button[type='button']",
);
cancelLogoutDialogButton.addEventListener("click", () => {
    confirmLogoutDialog.close();
});
