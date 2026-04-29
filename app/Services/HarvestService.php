<?php

namespace App\Services;

class HarvestService
{
    const UBINAN_AREA_SQM = 6.25; // 2.5m x 2.5m = 6.25 m²

    /**
     * Menghitung ton per hektar dari berat sampel (kg)
     */
    public static function calculateTonsPerHectare(float $sampleWeightKg): float
    {
        // Rumus: (sample_weight_kg / 6.25) * (10000 / 1000) = (sample_weight_kg / 6.25) * 10
        $tonsPerHa = ($sampleWeightKg / self::UBINAN_AREA_SQM) * 10;
        return round($tonsPerHa, 2);
    }

    /**
     * Menghitung total estimasi panen untuk suatu lahan (ton)
     */
    public static function calculateTotalYield(float $sampleWeightKg, float $landAreaHectares): float
    {
        $tonsPerHa = self::calculateTonsPerHectare($sampleWeightKg);
        $totalTons = $tonsPerHa * $landAreaHectares;
        return round($totalTons, 2);
    }

    /**
     * Menghitung nilai rupiah dari total hasil panen (ton) dan harga per kg
     */
    public static function calculateTotalValue(float $totalTons, float $pricePerKg): float
    {
        return round($totalTons * 1000 * $pricePerKg, 0);
    }
}