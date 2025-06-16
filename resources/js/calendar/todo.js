const form = document.querySelector("form.filters-row");
const clientSelect = document.querySelector("select[name='client']");
const staffSelect = document.querySelector("select[name='staff']");
const prioritySelect = document.querySelector("select[name='priority']");

prioritySelect.addEventListener("change", submitForm);
clientSelect.addEventListener("change", submitForm);
if (staffSelect) staffSelect.addEventListener("change", submitForm);

function submitForm() {
    form.submit();
}
