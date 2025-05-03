const ledgerAccounts = document.querySelectorAll(".ledger-account");
const mainForm = document.querySelector("#ledger-form")
const clientSelect = document.querySelector("#client-select");

ledgerAccounts.forEach((account) => {
    account.addEventListener("click", () => {
        const accountCode = account.dataset.accountCode;

        if (clientSelect.value === "") {
            mainForm.reportValidity();
        } else {
            mainForm.action = `${location.pathname}/${accountCode}/${clientSelect.value}`
            mainForm.submit();
        }
    })
})
