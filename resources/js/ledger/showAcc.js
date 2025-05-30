const dateRangeSelect = document.querySelector("#date-range-select");
const dateRangeDialog = document.querySelector("#set-custom-date-range-dialog");
const dateRangeForm = document.querySelector("#date-range-form");

dateRangeSelect.addEventListener("change", () => {
    const val = dateRangeSelect[dateRangeSelect.selectedIndex].value;
    switch (val) {
        case "custom":
            dateRangeDialog.showModal();
            break;
        case "all_time":
            location.href = location.origin + location.pathname;
            break;
        default:
            break;
    }
});

document.querySelector("#custom-option").addEventListener("click", () => {
    dateRangeDialog.showModal();
});

document.querySelector("#alltime-option").addEventListener("click", () => {
    location.href = location.origin + location.pathname;
});

document
    .querySelector(".submit-btn-wrapper button[type='button']")
    .addEventListener("click", () => {
        dateRangeDialog.close();
    });

document.querySelector("#back-button").addEventListener("click", () => {
    window.history.back();
});
