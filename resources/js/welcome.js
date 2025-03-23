const hamburger = document.querySelector(".header-sm .fa-bars");
const nav = document.querySelector(".nav-sm");
hamburger.addEventListener("click", (e) => {
    hamburger.classList.toggle("hidden");
    nav.classList.toggle("hidden");
});

document
    .querySelector(".header-side__account")
    .addEventListener("click", (e) => {
        window.alert("TODO: logout");
    });
