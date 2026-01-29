import { Controller } from "@hotwired/stimulus";

/**
 * Manages real-time invoice totals calculation.
 * * Listens for changes in the invoice items table and aggregates
 * Quantity, Price, and VAT to display global totals (HT, VAT, TTC).
 */

export default class extends Controller {
    static targets = ["row", "totalHt", "totalVat", "totalTtc"];

    connect() {
        this.recalculate();
    }

    /**
     * Aggregates values from all visible rows and updates the summary display.
     * This is triggered by input changes or row additions/removals.
     */
    recalculate() {
        let globalTotalHt = 0;
        let globalTotalVat = 0;
        this.rowTargets.forEach((row) => {
            const qtyInput = row.querySelector(".js-quantity");
            const priceInput = row.querySelector(".js-price");
            const vatInput = row.querySelector(".js-vat");

            const qty = qtyInput ? parseFloat(qtyInput.value) || 0 : 0;
            const price = priceInput ? parseFloat(priceInput.value) || 0 : 0;
            const vat = vatInput ? parseFloat(vatInput.value) || 0 : 0;

            const lineHt = qty * price;
            const lineVat = lineHt * (vat / 100);

            globalTotalHt += lineHt;
            globalTotalVat += lineVat;
        });

        const globalTotalTtc = globalTotalHt + globalTotalVat;

        /**
         * Updates the DOM elements with formatted currency values.
         */
        if (this.hasTotalHtTarget)
            this.totalHtTarget.innerText = this.formatMoney(globalTotalHt);
        if (this.hasTotalVatTarget)
            this.totalVatTarget.innerText = this.formatMoney(globalTotalVat);
        if (this.hasTotalTtcTarget)
            this.totalTtcTarget.innerText = this.formatMoney(globalTotalTtc);
    }

    formatMoney(amount) {
        return new Intl.NumberFormat("fr-FR", {
            style: "currency",
            currency: "EUR",
        }).format(amount);
    }
}
