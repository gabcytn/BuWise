const dropdowns = document.querySelectorAll(".nav-dropdown");

dropdowns.forEach((dropdown) => {
    dropdown.addEventListener("click", () => {
        const dropdownChildren = dropdown.parentNode.children[1].children;
        const arr = Array.from(dropdownChildren);

        arr.forEach((item) => {
            item.classList.toggle("d-none")
        })
    })
})
