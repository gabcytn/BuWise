const radioButtons = document.querySelectorAll("input[name='invoice-journal']");
const pageTitle = document.querySelector("#page-title");
const invoiceComponents = document.querySelector(".invoice-components");

invoiceComponents.classList.add("d-none");
radioButtons.forEach((radio) => {
    if (radio.value === "journal") {
        radio.checked = true;
    }

    radio.addEventListener("change", (e) => {
        if (e.target.checked) {
            pageTitle.textContent =
                e.target.value === "journal" ? "Journal Entry" : "Invoice";
            e.target.value === "journal"
                ? invoiceComponents.classList.add("d-none")
                : invoiceComponents.classList.remove("d-none");
        }
    });
});
