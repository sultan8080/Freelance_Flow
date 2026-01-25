<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\InvoiceRepository;

class InvoiceNumberGenerator
{
    public function __construct(
        private InvoiceRepository $invoiceRepository
    ) {}

    public function generateFor(User $user): string
    {
        $year = date('Y');
        // define the specific prefix for Invoices
        $prefix = sprintf('INV-%s-', $year);

        // find the highest number starting with 'INV-2026-"
        $lastNumber = $this->invoiceRepository->findLastInvoiceNumberForUser($user, $prefix);

        // 1. If no invoice exists for this year, start at 001
        if (!$lastNumber) {
            return $prefix . '001';
        }

        // 2. Extract the last 3 digits"
        $currentSequence = (int) substr($lastNumber, -3);

        // 3. Increment and re-pad with zeros
        $nextSequence = str_pad((string)($currentSequence + 1), 3, '0', STR_PAD_LEFT);

        return $prefix . $nextSequence;
    }
}