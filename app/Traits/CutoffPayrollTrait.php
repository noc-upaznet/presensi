<?php

namespace App\Traits;

use Carbon\Carbon;

trait CutoffPayrollTrait
{
    /**
     * Cutoff payroll 26 – 25
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    protected function getCutoff2625(int $year, int $month): array
    {
        $cutoffEnd = Carbon::create($year, $month, 25)->endOfDay();

        // Jika bulan berjalan & hari ini < 25 → pakai hari ini
        if ($year == now()->year && $month == now()->month && now()->day < 25) {
            $cutoffEnd = now();
        }

        $cutoffStart = $cutoffEnd->copy()
            ->subMonthNoOverflow()
            ->setDay(26)
            ->startOfDay();

        return [
            'start'      => $cutoffStart,
            'end'        => $cutoffEnd,
            'bulanTahun' => $cutoffEnd->format('Y-m'),
        ];
    }

    /**
     * Cutoff normal (1 – akhir bulan)
     */
    protected function getCutoffNormal(int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->startOfDay();

        $end = ($year == now()->year && $month == now()->month)
            ? now()
            : $start->copy()->endOfMonth();

        return [
            'start'      => $start,
            'end'        => $end,
            'bulanTahun' => $end->format('Y-m'),
        ];
    }

    /**
     * Helper untuk set cutoff sesuai mode
     */
    protected function resolveCutoff(
        int $year,
        int $month,
        string $type = 'cutoff_25'
    ): array {
        return $type === 'cutoff_normal'
            ? $this->getCutoffNormal($year, $month)
            : $this->getCutoff2625($year, $month);
    }
}
