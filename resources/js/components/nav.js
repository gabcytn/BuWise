const dropdowns = document.querySelectorAll(".dropdown");
dropdowns.forEach((dropdown) => {
    dropdown.addEventListener("click", () => {
        const ul = dropdown.querySelector("ul");
        ul.classList.toggle("d-block");
    });
});
