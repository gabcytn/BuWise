const addCompanyDialog = document.querySelector("#add-company-dialog");
document.querySelector("#add-company-btn").addEventListener("click", () => {
    addCompanyDialog.showModal();
});

document.querySelector("#close-dialog-btn").addEventListener("click", () => {
    addCompanyDialog.close();
});
