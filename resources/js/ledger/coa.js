const ledgerAccounts = document.querySelectorAll(".ledger-account");
const mainForm = document.querySelector("#ledger-form");
const clientSelect = document.querySelector("#client-select");

ledgerAccounts.forEach((account) => {
    account.addEventListener("click", () => {
        const accountCode = account.dataset.accountCode;

        if (clientSelect.value === "") {
            mainForm.reportValidity();
        } else {
            mainForm.action = `${location.pathname}/${accountCode}/${clientSelect.value}`;
            mainForm.submit();
        }
    });
});

document.querySelector("#search").addEventListener("input", (e) => {
    for (let i = 0; i < ledgerAccounts.length; i++) {
        const columns = ledgerAccounts[i].querySelectorAll("td");
        const searchText = e.target.value;
        const accountCode = columns[0].textContent.toLowerCase();
        const accountName = columns[1].textContent.toLowerCase();
        const accountGroup = columns[2].textContent.toLowerCase();
        if (
            accountCode.includes(searchText.toLowerCase()) ||
            accountName.includes(searchText.toLowerCase()) ||
            accountGroup.includes(searchText.toLowerCase())
        ) {
            ledgerAccounts[i].style.display = "";
        } else {
            ledgerAccounts[i].style.display = "none";
        }
    }
});
