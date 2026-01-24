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
        $lastNumber = $this->invoiceRepository->findLastInvoiceNumberForUser($user, $year);

        // If no invoice exists for this year, start at 001
        if (!$lastNumber) {
            return sprintf('FF-%s-001', $year);
        }

        // Extract the last 3 digits, increment, and re-pad with zeros
        $currentSequence = (int) substr($lastNumber, -3);
        $nextSequence = str_pad((string)($currentSequence + 1), 3, '0', STR_PAD_LEFT);

        return sprintf('FF-%s-%s', $year, $nextSequence);
    }
}