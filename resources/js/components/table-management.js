const rows = document.querySelectorAll(".striped");

rows.forEach((row) => {
    const rowNumber = row.dataset.rowNumber;
    if (rowNumber % 2 === 0) {
        row.style.backgroundColor = "var(--clear-white)"
    } else {
        row.style.backgroundColor = "var(--off-white)"
    }
})
