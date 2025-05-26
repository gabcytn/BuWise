const tableHeaders = document.querySelectorAll(".table-management thead tr th");
tableHeaders.forEach((header) => {
    header.dataset.id = header.textContent.replace(" ", "_").toLowerCase();
    header.addEventListener("click", (e) => {
        const url = new URL(location.href);
        const searchParams = url.searchParams;
        searchParams.set("sort", e.target.dataset.id);
        url.search = searchParams.toString();
        const newUrl = url.toString();

        location.href = newUrl;
    });
});
