import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

let CLICKED_ITEM;

document.querySelectorAll(".invoice-card #created-at").forEach((item) => {
    const textContent = item.textContent;
    item.innerHTML =
        "<strong>Created at: </strong>" + dayjs().to(dayjs(textContent));
});

const dialog = document.querySelector("#delete-item-dialog");
document.querySelectorAll(".invoice-card").forEach((item) => {
    item.addEventListener("click", () => {
        CLICKED_ITEM = item;
        dialog.showModal();
    });
});

dialog.querySelector("button[type='button']").addEventListener("click", () => {
    dialog.close();
});

dialog.querySelector("form").addEventListener("submit", (e) => {
    e.preventDefault();
    deleteItem(CLICKED_ITEM.dataset.invoiceId);
    CLICKED_ITEM.remove();
    dialog.close();
});

async function deleteItem(itemId) {
    await fetch(`/invoices/failed/${itemId}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
    });
}
