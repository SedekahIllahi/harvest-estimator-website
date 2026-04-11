<?php

namespace App\Services;

class HarvestService
{
    /**
     * Ubinan math: Converts a 2.5m x 2.5m sample into Tons per Hectare
     */
    public function calculateYield($sampleWeightKg, $landAreaHectares)
    {
        // 1. Standard Ubinan plot is 6.25 square meters (2.5m x 2.5m)
        $ubinanAreaSqm = 6.25; 
        
        // 2. Convert their total land from Hectares to Square Meters
        $totalAreaSqm = $landAreaHectares * 10000;
        
        // 3. How many Ubinan plots fit in their land?
        $multiplier = $totalAreaSqm / $ubinanAreaSqm;
        
        // 4. Multiply that by the sample weight to get Total KG
        $estimatedTotalKg = $multiplier * $sampleWeightKg;
        
        // 5. Convert to Tons (Divide by 1000)
        $estimatedTons = $estimatedTotalKg / 1000;

        return round($estimatedTons, 2);
    }
}