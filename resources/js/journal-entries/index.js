const tr = document.querySelectorAll(".table-row");

tr.forEach((row) => {
    row.addEventListener("click", () => {
        location = row.dataset.url;
    });
});
