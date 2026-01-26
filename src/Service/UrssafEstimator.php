<?php

namespace App\Service;

class UrssafEstimator
{
    // Current Auto-Entrepreneur rate for services (BNC) in France (2024-2026)
    // 21.1% or 21.2% usually (varies slightly by year, fixed here at 21.2%)
    private const RATE_PERCENTAGE = '21.2';

    /**
     * Calculates the estimated social charges based on Total HT.
     * Formula: TotalHT * 21.2 / 100
     */
    public function calculateCharges(string $totalHt): string
    {
        // BCMath for precision, assuming you have the extension enabled       
        $rate = bcdiv(self::RATE_PERCENTAGE, '100', 4); // 0.2120
        return bcmul($totalHt, $rate, 2);
    }
}