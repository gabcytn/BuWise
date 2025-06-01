document.addEventListener("DOMContentLoaded", () => {
    const assetsTotal = calculateTotalAssets();
    const liabilitiesTotal = calculateTotalLiabilities();
    const equitiesTotal = calculateTotalEquities();

    document.querySelector(".assets-total").textContent =
        assetsTotal.toFixed(2);
    document.querySelector(".liabilities-total").textContent =
        liabilitiesTotal.toFixed(2);
    document.querySelector(".equities-total").textContent =
        equitiesTotal.toFixed(2);
});

function calculateTotalAssets() {
    const assets = document.querySelectorAll(".assets");
    let assetsTotal = 0;

    assets.forEach((asset) => {
        assetsTotal += parseFloat(asset.textContent);
    });

    return assetsTotal;
}

function calculateTotalLiabilities() {
    const liabilities = document.querySelectorAll(".liabilities");
    let liabilitiesTotal = 0;

    liabilities.forEach((liability) => {
        liabilitiesTotal += parseFloat(liability.textContent);
    });

    return liabilitiesTotal;
}
function calculateTotalEquities() {
    const equities = document.querySelectorAll(".equities");
    let equitiesTotal = 0;

    equities.forEach((equity) => {
        equitiesTotal += parseFloat(equity.textContent);
    });

    return equitiesTotal;
}
