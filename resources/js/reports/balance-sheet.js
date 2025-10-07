document.addEventListener("DOMContentLoaded", () => {
    const assetsTotal = calculateSum(".assets");
    const liabilitiesTotal = calculateSum(".liabilities");
    const equitiesTotal = calculateSum(".equities");

    document.querySelector(".assets-total strong").textContent = formatNumber(
        assetsTotal,
        2,
    );
    document.querySelector(".liabilities-total").textContent = formatNumber(
        liabilitiesTotal,
        2,
    );
    document.querySelector(".equities-total").textContent = formatNumber(
        equitiesTotal,
        2,
    );

    document.querySelector("#net").textContent = formatNumber(
        liabilitiesTotal + equitiesTotal,
        2,
    );
});

function calculateSum(className) {
    const list = document.querySelectorAll(className);
    let total = 0;

    list.forEach((item) => {
        const currentValue = parseFloat(item.textContent.replaceAll(",", ""));
        total += currentValue;
        if (currentValue < 0) item.style.color = "red";
    });

    return total;
}

function formatNumber(number, decimals) {
    return Number(number).toLocaleString("en-US", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}

document.querySelectorAll(".clickable").forEach((item) => {
    item.addEventListener("click", () => {
        location.href = item.dataset.redirect;
    });
});
