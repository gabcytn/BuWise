const ledgerAccounts = document.querySelectorAll(".ledger-account");
const mainForm = document.querySelector("#ledger-form");
const clientSelect = document.querySelector(".client-select");

// Handle account row clicks
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

// ✅ Fix: Attach the search event to the input field, not the image
document.querySelector("#account-search").addEventListener("input", (e) => {
    const searchText = e.target.value.toLowerCase();

    for (let i = 0; i < ledgerAccounts.length; i++) {
        const columns = ledgerAccounts[i].querySelectorAll("td");
        const accountCode = columns[0].textContent.toLowerCase();
        const accountName = columns[1].textContent.toLowerCase();

        if (
            accountCode.includes(searchText) ||
            accountName.includes(searchText)
        ) {
            ledgerAccounts[i].style.display = "";
        } else {
            ledgerAccounts[i].style.display = "none";
        }
    }
});

const dialog = document.querySelector("#add-new-account");
document.querySelector(".add-account-btn").addEventListener("click", () => {
    dialog.showModal();
});
document
    .querySelector("#close-account-dialog-btn")
    .addEventListener("click", () => {
        dialog.close();
    });

const accountType = document.querySelector("select#account-type");
const accountCode = document.querySelector("input#account-code");

accountCode.addEventListener("input", validateAccountCodePrefix);
accountType.addEventListener("change", validateAccountCodePrefix);

function validateAccountCodePrefix() {
    const accountTypeValue = accountType[accountType.selectedIndex].value;
    if (
        accountCode.value &&
        accountTypeValue &&
        !accountCode.value.startsWith(accountTypeValue)
    ) {
        alert(`Account Code should start with a ${accountTypeValue}`);
        accountCode.value = "";
    }
}

document.querySelector(".type-select").addEventListener("change", (e) => {
    for (let i = 0; i < ledgerAccounts.length; i++) {
        const columns = ledgerAccounts[i].querySelectorAll("td");
        const accountGroup = columns[2].textContent.toLowerCase();
        if (e.target.value === "all") ledgerAccounts[i].style.display = "";
        else if (accountGroup.includes(e.target.value))
            ledgerAccounts[i].style.display = "";
        else ledgerAccounts[i].style.display = "none";
    }
});
