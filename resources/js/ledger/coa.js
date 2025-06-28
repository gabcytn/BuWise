const ledgerAccounts = document.querySelectorAll(".ledger-account");
const mainForm = document.querySelector("#ledger-form");
const clientSelect = document.querySelector(".client-select");

// Handle account row clicks
ledgerAccounts.forEach((account) => {
    account.addEventListener("click", (e) => {
        const accountCode = account.dataset.accountCode;
        if (e.target.classList.contains(".fa-solid")) {
            return;
        }

        if (clientSelect.value === "") {
            mainForm.reportValidity();
        } else {
            mainForm.action = `${location.pathname}/${accountCode}/${clientSelect.value}`;
            mainForm.submit();
        }
    });
});

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
    const accountTypeValue = getAccountTypePrefix(
        accountType[accountType.selectedIndex].value,
    );
    if (accountTypeValue === "0") {
        alert("Invalid account type value!");
        return;
    }
    if (
        accountCode.value &&
        accountTypeValue &&
        !accountCode.value.startsWith(accountTypeValue)
    ) {
        alert(`Account Code should start with a ${accountTypeValue}`);
        accountCode.value = "";
    }
}

function getAccountTypePrefix(type) {
    switch (type) {
        case "asset":
        case "cash":
        case "receivable":
            return "1";
        case "liability":
        case "payable":
            return "2";
        case "equity":
            return "3";
        case "revenue":
            return "4";
        case "expense":
            return "5";
        default:
            return "0";
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

let FORM_SELECTED;

const deleteDialog = document.querySelector("dialog#delete-account");
document.querySelectorAll(".action-column form").forEach((form) => {
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        FORM_SELECTED = form;
        deleteDialog.showModal();
    });
});

const confirmDeleteButton = deleteDialog.querySelector("button[type='submit']");
deleteDialog
    .querySelector("input#confirmation")
    .addEventListener("input", (e) => {
        const v = e.target.value;
        if (v === "permanently delete") {
            confirmDeleteButton.disabled = false;
        } else {
            confirmDeleteButton.disabled = true;
        }
    });

deleteDialog
    .querySelector("button[type='button']")
    .addEventListener("click", () => {
        deleteDialog.close();
    });

deleteDialog.querySelector("form").addEventListener("submit", (e) => {
    e.preventDefault();
    FORM_SELECTED.submit();
});
