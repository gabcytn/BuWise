const btn = document.querySelector("#set-initial-balance-btn");
const cancelBtn = document.querySelector("#set-initial-balance-dialog button[type='button']");
const dialog = document.querySelector("#set-initial-balance-dialog");

btn.addEventListener("click", () => {
    dialog.showModal();
})

cancelBtn.addEventListener("click", () => {
    dialog.close();
})

const dateRangeSelect = document.querySelector("#date-range-select");
const dateRangeDialog = document.querySelector("#set-custom-date-range-dialog");
const dateRangeForm = document.querySelector("#date-range-form");

dateRangeSelect.addEventListener("change", () => {
    const val = dateRangeSelect[dateRangeSelect.selectedIndex].value;
    if (val === "custom") {
        dateRangeDialog.showModal();
    }
});

document.querySelector("#custom-option").addEventListener("click", () => {
    dateRangeDialog.showModal();
});

document.querySelector(".submit-btn-wrapper button[type='button']").addEventListener("click", () => {
    dateRangeDialog.close();
});
