<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function topInstruments(Request $request, $period): JsonResponse
    {
        $validPeriods = ['daily', 'weekly', 'monthly'];
        if (!in_array($period, $validPeriods)) {
            return response()->json(['error' => 'Invalid period. Allowed values: daily, weekly, monthly'], 400);
        }

        // Cache key and TTL (3600 seconds = 1 hour)
        $cacheKey = "top_instruments_{$period}";
        $ttl = 3600;

        $data = Cache::remember($cacheKey, $ttl, function () use ($period) {
            return $this->fetchTopInstruments($period);
        });

        return response()->json($data, 200);
    }

    private function fetchTopInstruments($period)
    {
        $startOfPeriod = match ($period) {
            'daily' => now()->startOfDay(),
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
        };

        // Query instruments along with their mention counts in the given period
        // InstrumentMention contains `mentioned_at`, we can filter by that
        return Instrument::withCount(['mentions' => function ($query) use ($startOfPeriod) {
            $query->where('mentioned_at', '>=', $startOfPeriod);
        }])
            ->orderBy('mentions_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($instrument) {
                return [
                    'instrument' => $instrument->symbol,
                    'mentions' => $instrument->mentions_count
                ];
            })
            ->toArray();
    }
}
