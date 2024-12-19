<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\StatsService;


class StatsController extends Controller
{
    private $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    public function getTopInstruments($period): JsonResponse
    {
        $validPeriods = ['daily', 'weekly', 'monthly'];

        if (!in_array($period, $validPeriods)) {
            return response()->json(['error' => 'Invalid period. Use daily, weekly, or monthly.'], 400);
        }

        $topInstruments = $this->statsService->getTopInstruments($period);
        return response()->json($topInstruments, 200);
    }
}
