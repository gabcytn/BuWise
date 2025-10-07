document.addEventListener("DOMContentLoaded", () => {
    const revenueTotal = calculateSum(".revenues");
    document.querySelector(".revenues-total").innerText = formatNumber(
        revenueTotal,
        2,
    );

    const expenseTotal = calculateSum(".expenses");
    document.querySelector(".expenses-total").innerText = formatNumber(
        expenseTotal,
        2,
    );

    const net = document.querySelector("#net");
    net.innerText = formatNumber(revenueTotal - expenseTotal, 2);

    if (revenueTotal - expenseTotal < 0) {
        const red = "#DD0000";
        net.style.color = red;
        net.parentNode.parentNode.children[0].style.color = red;
    }
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
