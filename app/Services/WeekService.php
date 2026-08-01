<?php

namespace App\Services;

use App\Models\Setting;
use Carbon\Carbon;

class WeekService
{
    public function startDate(): Carbon
    {
        $date = Setting::get('cash_start_date', config('kaskelas.start_date'));
        return Carbon::parse($date)->startOfDay();
    }

    public function weeklyAmount(): int
    {
        return (int) Setting::get('weekly_amount', config('kaskelas.weekly_amount'));
    }

    /**
     * Return the Monday that begins the week containing $date.
     * The very first week is anchored to the configured start date,
     * so weeks are always computed as start_date + (n * 7 days).
     */
    public function weekStartFor(Carbon|string $date): Carbon
    {
        $date = Carbon::parse($date)->startOfDay();
        $start = $this->startDate();

        if ($date->lessThan($start)) {
            return $start->copy();
        }

        $diffDays = $start->diffInDays($date);
        $weeksElapsed = intdiv($diffDays, 7);

        return $start->copy()->addWeeks($weeksElapsed);
    }

    public function currentWeekStart(): Carbon
    {
        return $this->weekStartFor(Carbon::now());
    }

    public function weekEnd(Carbon $weekStart): Carbon
    {
        return $weekStart->copy()->addDays(6);
    }

    public function weekLabel(Carbon $weekStart): string
    {
        $end = $this->weekEnd($weekStart);
        return $weekStart->translatedFormat('d M Y') . ' – ' . $end->translatedFormat('d M Y');
    }

    /**
     * Generate a list of weeks from the start date up to (and including)
     * the week containing $upTo (defaults to today), for the week picker dropdown.
     *
     * @return array<int, array{index:int, start:Carbon, end:Carbon, label:string}>
     */
    public function generateWeeks(?Carbon $upTo = null): array
    {
        $start = $this->startDate();
        $upTo = $upTo ?? Carbon::now();
        $lastWeekStart = $this->weekStartFor($upTo);

        $weeks = [];
        $cursor = $start->copy();
        $index = 1;

        while ($cursor->lessThanOrEqualTo($lastWeekStart)) {
            $weeks[] = [
                'index' => $index,
                'start' => $cursor->copy(),
                'end' => $this->weekEnd($cursor),
                'label' => 'Week ' . $index . ' (' . $this->weekLabel($cursor) . ')',
            ];
            $cursor->addWeek();
            $index++;
        }

        return array_reverse($weeks);
    }
}
