@vite(['resources/css/components/csv-upload-dialog.css'])
@props(['clients'])
<dialog id="csv-dialog">
    <h2>Import Journal Entries</h2>
    <div class="format-instructions">
        <h5>Excel Format Required:</h5>
        <p>Column A: Reference Number</p>
        <p>Column B: Date Issued</p>
        <p>Column C: Description</p>
        <p>Column D: Amount</p>
        <p>Column E: VAT</p>
        <p>Column F: Total (Amount + VAT)</p>
    </div>
    <form action="/journal-entries/csv" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="dialog-select-container">
            <div class="dialog-input-box">
                <label for="csv">Excel file</label>
                <input name="csv" required id="csv" type="file" />
            </div>
            <div class="dialog-input-box">
                <label for="transaction-type">Transaction Type</label>
                <select name="transaction_type" id="transaction-type" required>
                    <option value="sales">Sales</option>
                    <option value="purchases">Purchases</option>
                </select>
            </div>
            <div class="dialog-input-box">
                <label for="client-select">Client</label>
                <select name="client" id="client-select" required>
                    <option value="" selected disabled>Select a client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="dialog-button-container">
            <button type="submit">Submit</button>
            <button type="button">Cancel</button>
        </div>
    </form>
</dialog>
<script>
    const dialog = document.querySelector("#csv-dialog");
    document.querySelector(".dropdown-import").addEventListener("click", () => {
        dialog.showModal();
    });

    dialog
        .querySelector("button[type='button']")
        .addEventListener("click", () => {
            dialog.close();
        });
</script>
