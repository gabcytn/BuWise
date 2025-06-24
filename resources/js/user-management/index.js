const deleteItemDialog = document.querySelector(".delete-item-dialog");
const closeDeleteItemDialogButton = document.querySelector(
    ".delete-item-dialog button[type='button']",
);
const deleteItemForm = document.querySelector(".delete-item-dialog form");
document.querySelectorAll("#delete-form").forEach((item) => {
    item.addEventListener("submit", (e) => {
        e.preventDefault();

        deleteItemForm.action = item.action;
        deleteItemDialog.showModal();
    });
});

closeDeleteItemDialogButton.addEventListener("click", () => {
    deleteItemForm.action = "#";
    deleteItemDialog.close();
});

// FILTERS
const selectElement = document.querySelector(".type-select");
const resetFilter = document.querySelector(".refresh-button");

resetFilter.style.cursor = "pointer";
resetFilter.addEventListener("click", () => {
    const currentUrl = window.location.href;
    window.location.href = currentUrl.split("?")[0];
});

selectElement.addEventListener("change", (e) => {
    const selectedFilter =
        selectElement.options[selectElement.selectedIndex].value;
    const currentUrl = window.location.href.split("?")[0];
    window.location.href = currentUrl + `?filter=${selectedFilter}`;
});
