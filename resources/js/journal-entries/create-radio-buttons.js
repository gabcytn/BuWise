const radioButtons = document.querySelectorAll("input[name='invoice-journal']");
const pageTitle = document.querySelector("#page-title");
const invoiceComponents = document.querySelector(".invoice-components");
const clientSelectWrapper = document.querySelector(".client-select-wrapper");

invoiceComponents.classList.add("d-none");
radioButtons.forEach((radio) => {
    if (radio.value === "journal") {
        radio.checked = true;
    }

    radio.addEventListener("change", (e) => {
        if (!e.target.checked) {
            return;
        }

        pageTitle.textContent =
            e.target.value === "journal" ? "Journal Entry" : "Invoice";
        e.target.value === "journal"
            ? invoiceComponents.classList.add("d-none")
            : invoiceComponents.classList.remove("d-none");
        if (e.target.value === "invoice") {
            clientSelectWrapper.classList.add("d-none");
            clientSelectWrapper.querySelector("select").disabled = true;
            clientSelectWrapper.querySelector("select").required = false;
            invoiceComponents.querySelector("input").required = true;
            invoiceComponents.querySelector("input").disabled = false;
        } else {
            clientSelectWrapper.classList.remove("d-none");
            clientSelectWrapper.querySelector("select").disabled = false;
            clientSelectWrapper.querySelector("select").required = true;
            invoiceComponents.querySelector("input").required = false;
            invoiceComponents.querySelector("input").disabled = true;
        }
    });
});
