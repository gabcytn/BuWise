const dateRangeSelect = document.querySelector("#date-range-select");
const dateRangeDialog = document.querySelector("#set-custom-date-range-dialog");
const startDate = document.querySelector("#start_date");
const endDate = document.querySelector("#end_date");

dateRangeSelect.addEventListener("change", () => {
    const val = dateRangeSelect[dateRangeSelect.selectedIndex].value;
    switch (val) {
        case "custom":
            dateRangeDialog.showModal();
            break;
        case "all_time":
            startDate.value = "";
            endDate.value = "";
            break;
        default:
            break;
    }
});

const start = document.querySelector("input#start");
const end = document.querySelector("input#end");

// hydrate the hidden input fields
document.querySelector("#date-range-form").addEventListener("submit", (e) => {
    e.preventDefault();
    startDate.value = start.value;
    endDate.value = end.value;
    dateRangeDialog.close();
});

document.querySelector("#custom-option").addEventListener("click", () => {
    dateRangeDialog.showModal();
});
document
    .querySelector(".submit-btn-wrapper button[type='button']")
    .addEventListener("click", () => {
        dateRangeDialog.close();
    });

// exclude the start_date & end_date in url params if date range is all time
document.querySelector("#report-form").addEventListener("submit", (e) => {
    e.preventDefault();
    if (!startDate.value && !endDate.value) {
        startDate.disabled = true;
        endDate.disabled = true;
    }
    e.target.submit();
});
