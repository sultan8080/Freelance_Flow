<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Entity\InvoiceItem;

class InvoiceCalculator
{
    /**
     * Number of decimal places used in all BCMath operations. * We explicitly define this instead of relying on php.ini defaults
     * to guarantee consistent financial rounding.
     */
    private int $scale;

    public function __construct(int $scale = 2)
    {
        $this->scale = $scale;
    }

    /**
     * Calculates all totals for the entire invoice.
     * - Iterates through each InvoiceItem
     * - Calculates item-level totals (HT, VAT, TTC)
     * - Aggregates totals into the Invoice entity
     */
    public function calculateInvoice(Invoice $invoice): void
    {
        // Running totals for the invoice
        $totalHt = '0.00';
        $totalVat = '0.00';
        $totalTtc = '0.00';

        /** @var InvoiceItem $item */
        foreach ($invoice->getInvoiceItems() as $item) {

            // Ensure each item has correct HT, VAT, and TTC values
            $this->calculateItem($item);

            // Add item totals to invoice totals
            $totalHt  = bcadd($totalHt,  $item->getTotalHt() ?? '0.00', $this->scale);
            $totalVat = bcadd($totalVat, $item->getVatAmount() ?? '0.00', $this->scale);
            $totalTtc = bcadd($totalTtc, $item->getTotalTtc() ?? '0.00', $this->scale);
        }

        // Update invoice totals
        $invoice->setTotalHt($totalHt);
        $invoice->setTotalVat($totalVat);
        $invoice->setTotalAmount($totalTtc);
    }

    /**
     * Recalculates totals for a single invoice item.
     * - totalHt = unitPrice * quantity
     * - vatAmount = totalHt * (vatRate / 100)
     * - totalTtc = totalHt + vatAmount
     *
     * BCMath is used to ensure precise decimal arithmetic.
     */
    public function calculateItem(InvoiceItem $item): void
    {
        $unitPrice = $item->getUnitPrice() ?? '0.00'; 
        $quantity  = (string) ($item->getQuantity() ?? 0.0);
        $vatRate   = (string) ($item->getVatRate() ?? 0.0);  

        /**
         * Calculate HT (Hors Taxe)
         * Example: 50.00 * 2 = 100.00
         */
        $totalHt = bcmul($unitPrice, $quantity, $this->scale);

        /**
         * Calculate VAT amount
         * vatRate is divided by 100 to convert percentage to multiplier
         * Example: 20 / 100 = 0.20
         */
        $vatAmount = bcmul(
            $totalHt,
            bcdiv($vatRate, '100', $this->scale),
            $this->scale
        );

        /**
         * Calculate TTC (Toutes Taxes Comprises)
         * Example: 100.00 + 20.00 = 120.00
         */
        $totalTtc = bcadd($totalHt, $vatAmount, $this->scale);

        // Update the item entity with computed values
        $item->setTotalHt($totalHt);
        $item->setVatAmount($vatAmount);
        $item->setTotalTtc($totalTtc);
    }
}
