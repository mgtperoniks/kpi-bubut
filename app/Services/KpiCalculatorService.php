<?php

namespace App\Services;

class KpiCalculatorService
{
    /**
     * Hitung total jam kerja dari jam mulai dan jam selesai
     * Aman untuk lintas hari
     */
    public static function workHours(string $start, string $end): float
    {
        $startTime = strtotime($start);
        $endTime   = strtotime($end);

        if (!$startTime || !$endTime) {
            return 0;
        }

        // Handle lintas hari (misal 22:00 - 06:00)
        if ($endTime <= $startTime) {
            $endTime += 86400;
        }

        $seconds = $endTime - $startTime;

        if ($seconds <= 0) {
            return 0;
        }

        return round($seconds / 3600, 2);
    }

    /**
     * Hitung target quantity
     * Proteksi total dari input tidak valid
     */
    public static function targetQty(float $workHours, int $cycleTimeSec): int
    {
        if ($workHours <= 0 || $cycleTimeSec <= 0) {
            return 0;
        }

        $target = $workHours * (3600 / $cycleTimeSec);

        if ($target < 0 || !is_finite($target)) {
            return 0;
        }

        return (int) floor($target);
    }

    /**
     * Hitung achievement (%)
     * Tidak pernah menghasilkan ∞ atau NaN
     */
    public static function achievement(int $actual, int $target): float
    {
        if ($actual < 0 || $target <= 0) {
            return 0;
        }

        $achievement = ($actual / $target) * 100;

        if (!is_finite($achievement) || $achievement < 0) {
            return 0;
        }

        return round($achievement, 2);
    }

    /**
     * Helper aman: hitung target & achievement sekaligus
     * (opsional, untuk future-proof controller)
     */
    public static function calculateKpi(
        float $workHours,
        int $cycleTimeSec,
        int $actualQty
    ): array {
        if ($workHours <= 0 || $cycleTimeSec <= 0) {
            return [
                'target_qty'  => 0,
                'achievement' => 0,
            ];
        }

        $target = self::targetQty($workHours, $cycleTimeSec);
        $achievement = self::achievement($actualQty, $target);

        return [
            'target_qty'  => $target,
            'achievement' => $achievement,
        ];
    }
}
