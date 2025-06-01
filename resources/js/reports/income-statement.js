document.addEventListener("DOMContentLoaded", () => {
    const revenues = document.querySelectorAll(".revenues");
    let revenueTotal = 0;
    revenues.forEach((revenue) => {
        revenueTotal += parseFloat(revenue.textContent);
    });

    document.querySelector(".revenues-total").textContent =
        revenueTotal.toFixed(2);

    const expenses = document.querySelectorAll(".expenses");
    let expenseTotal = 0;
    expenses.forEach((expense) => {
        expenseTotal += parseFloat(expense.textContent);
    });

    document.querySelector(".expenses-total").textContent =
        expenseTotal.toFixed(2);

    document.querySelector("#net").textContent = (
        revenueTotal - expenseTotal
    ).toFixed(2);
});
