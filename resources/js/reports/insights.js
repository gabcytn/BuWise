import { getCashFlowData } from "./insights/cash-flow.js";
import { getReceivablesData } from "./insights/receivables.js";
import { getPayablesData } from "./insights/payables.js";
import { getPLData } from "./insights/profit-and-loss.js";

document
    .querySelector(".insights-header form")
    .addEventListener("submit", async (e) => {
        e.preventDefault();
        const selectClient = document.querySelector("select[name='client']");
        const client = selectClient[selectClient.selectedIndex].value;
        await getCashFlowData(client);
        await getReceivablesData(client);
        await getPayablesData(client);
        await getPLData(client);
        document
            .querySelector(".insights-body.d-none")
            .classList.remove("d-none");
    });
