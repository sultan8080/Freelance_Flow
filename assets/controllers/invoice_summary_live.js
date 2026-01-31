import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = [
        "row",
        "totalHt",
        "totalVat",
        "totalTtc",
        "urssaf",
        "net",
    ];

    connect() {
        this.recalculate();
    }

    recalculate() {
        let totalHt = 0;
        let totalVat = 0;

        // 1. Calculate Rows
        this.rowTargets.forEach((row) => {
            const qty =
                parseFloat(row.querySelector(".js-quantity").value) || 0;
            const price = parseFloat(row.querySelector(".js-price").value) || 0;
            const vatRate = parseFloat(row.querySelector(".js-vat").value) || 0;

            const rowTotal = qty * price;
            totalHt += rowTotal;
            totalVat += rowTotal * (vatRate / 100);
        });

        // 2. Update Amount Totals (HT, VAT, TTC)

        this.totalHtTarget.innerText = totalHt.toFixed(2) + " €";
        this.totalVatTarget.innerText = totalVat.toFixed(2) + " €";
        this.totalTtcTarget.innerText = (totalHt + totalVat).toFixed(2) + " €";

        // 3. Update Private Insight (URSSAF)
        if (this.hasUrssafTarget && this.hasNetTarget) {
            const urssaf = totalHt * 0.212;
            this.urssafTarget.innerText = urssaf.toFixed(2) + " €";
            this.netTarget.innerText = (totalHt - urssaf).toFixed(2) + " €";
        }
    }
}
