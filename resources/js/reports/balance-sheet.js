document.addEventListener("DOMContentLoaded", () => {
    const assetsTotal = calculateTotalAssets();
    const liabilitiesTotal = calculateTotalLiabilities();
    const equitiesTotal = calculateTotalEquities();

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

function calculateTotalAssets() {
    const assets = document.querySelectorAll(".assets");
    let assetsTotal = 0;

    assets.forEach((asset) => {
        assetsTotal += parseFloat(asset.textContent.replaceAll(",", ""));
    });

    return assetsTotal;
}

function calculateTotalLiabilities() {
    const liabilities = document.querySelectorAll(".liabilities");
    let liabilitiesTotal = 0;

    liabilities.forEach((liability) => {
        liabilitiesTotal += parseFloat(
            liability.textContent.replaceAll(",", ""),
        );
    });

    return liabilitiesTotal;
}

function calculateTotalEquities() {
    const equities = document.querySelectorAll(".equities");
    let equitiesTotal = 0;

    equities.forEach((equity) => {
        equitiesTotal += parseFloat(equity.textContent.replaceAll(",", ""));
    });

    return equitiesTotal;
}

function formatNumber(number, decimals) {
    return Number(number).toLocaleString("en-US", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}
