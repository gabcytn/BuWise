@props(['id'])
@vite(['resources/css/components/accounts-informational-dialog.css'])
<dialog id={{ $id }}>
    <section class="accinfo-section">
        <div class="accinfo-header">
            <h1>Knowing your accounts</h1>
            <i class="fa-solid fa-xmark"></i>
        </div>
        <p>In accounting, transactions are organized into account groups to make financial reporting clear, consistent,
            and
            easy to understand. These groups act as categories that explain where money comes from, where it goes, and
            how
            it impacts a business’s financial position. The most common account groups are:</p>
    </section>

    <section class="accinfo-section">
        <h3>Assets</h3>
        <p>Resources owned by the business that provide future benefits, such as cash, receivables, inventory, property,
            and
            equipment. Assets can be current (easily converted to cash within a year) or non-current (long-term items
            like
            land or machinery).</h3>
    </section>
    <section class="accinfo-section">
        <h3>Liabilitiess</h3>
        <p>Obligations the business owes to others, including loans, accounts payable, salaries payable, and taxes due.
            Liabilities may be current (due within a year) or long-term (due after a year).</h3>
    </section>
    <section class="accinfo-section">
        <h3>Equities</h3>
        <p> The owners’ share in the business after liabilities are deducted from assets. It includes capital invested,
            retained earnings, and other reserves, representing the company’s net worth.</h3>
    </section>
    <section class="accinfo-section">
        <h3>Revenues</h3>
        <p>Income earned from the company’s main operations, such as sales of goods or services. Revenue reflects how
            well
            the business generates income.</h3>
    </section>
    <section class="accinfo-section">
        <h3>Expenses</h3>
        <p>Costs of running the business, like rent, salaries, utilities, and supplies. Expenses reduce profit and are
            compared with revenues to measure performance.</h3>
    </section>
</dialog>

<script>
    const dialog = document.querySelector("#{{ $id }}");
    document.querySelector(".more-btn").addEventListener("click", () => {
        dialog.showModal();
    })
    dialog.querySelector(".fa-xmark").addEventListener("click", () => {
        dialog.close();
    })
</script>
