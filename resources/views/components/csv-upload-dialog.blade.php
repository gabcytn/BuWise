@vite(['resources/css/components/csv-upload-dialog.css'])
@props(['clients'])
<dialog id="csv-dialog">
    <h2>Upload CSV</h2>
    <form action="/journal-entries/csv" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="dialog-select-container">
            <input name="csv" required id="csv" type="file" />
            <select name="transaction_type" required>
                <option value="sales">Sales</option>
                <option value="purchases">Purchases</option>
            </select>
            <select name="client" required>
                <option value="" selected disabled>Select a client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="dialog-button-container">
            <button type="submit">Submit</button>
            <button type="button">Cancel</button>
        </div>
    </form>
</dialog>
<script>
    document.querySelector(".dropdown-import").addEventListener("click", () => {
        document.querySelector("#csv-dialog").showModal();
    });

    document
        .querySelector("#csv-dialog button[type='button']")
        .addEventListener("click", () => {
            document.querySelector("#csv-dialog").close();
        });
</script>
