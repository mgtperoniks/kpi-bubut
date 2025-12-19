<?php

namespace App\Services;

class KpiCalculatorService
{
    /**
     * Hitung total jam kerja dari jam mulai dan jam selesai
     */
    public static function workHours(string $start, string $end): float
    {
        $startTime = strtotime($start);
        $endTime   = strtotime($end);

        if (!$startTime || !$endTime || $endTime <= $startTime) {
            return 0;
        }

        return round(($endTime - $startTime) / 3600, 2);
    }

    /**
     * Hitung target quantity berdasarkan jam kerja dan cycle time (detik)
     */
    public static function targetQty(float $workHours, int $cycleTimeSec): int
    {
        if ($workHours <= 0 || $cycleTimeSec <= 0) {
            return 0;
        }

        return (int) floor($workHours * (3600 / $cycleTimeSec));
    }

    /**
     * Hitung achievement (%) dari actual vs target
     */
    public static function achievement(int $actual, int $target): float
    {
        if ($actual < 0 || $target <= 0) {
            return 0;
        }

        return round(($actual / $target) * 100, 2);
    }
}
