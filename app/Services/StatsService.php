<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Instrument;

class StatsService
{
    public function getTopInstruments(string $period): array
    {
        // Cache key based on period (e.g., top_instruments_daily, top_instruments_weekly)
        $cacheKey = "top_instruments_{$period}";

        // Cache duration (1 hour)
        $cacheDuration = 3600; // seconds

        // Use Cache::remember to either return cached results or fetch from the database
        return Cache::remember($cacheKey, $cacheDuration, function () use ($period) {
            return $this->fetchTopInstrumentsFromDB($period);
        });
    }

    private function fetchTopInstrumentsFromDB(string $period): array
    {
        // Define start of the period based on the input
        $startPeriod = match ($period) {
            'daily' => now()->startOfDay(),
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            default => now()->startOfDay(),
        };

        // Query instruments with mentions count in the given period
        return Instrument::withCount(['mentions' => function ($query) use ($startPeriod) {
            $query->where('mentioned_at', '>=', $startPeriod);
        }])
            ->orderByDesc('mentions_count')
            ->take(10)
            ->get()
            ->toArray();
    }
}
