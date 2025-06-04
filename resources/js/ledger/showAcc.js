const dateRangeSelect = document.querySelector("#date-range-select");
const dateRangeDialog = document.querySelector("#set-custom-date-range-dialog");
const dateRangeForm = document.querySelector("#date-range-form");

dateRangeSelect.addEventListener("change", () => {
    const val = dateRangeSelect[dateRangeSelect.selectedIndex].value;
    if (val === "custom") dateRangeDialog.showModal();
});

document.querySelector("#custom-option").addEventListener("click", () => {
    dateRangeDialog.showModal();
});

document
    .querySelector(".submit-btn-wrapper button[type='button']")
    .addEventListener("click", () => {
        dateRangeDialog.close();
    });

document.querySelector("#back-button").addEventListener("click", () => {
    window.history.back();
});
