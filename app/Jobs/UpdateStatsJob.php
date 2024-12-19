<?php

namespace App\Jobs;

use App\Models\Instrument;
use App\Models\InstrumentMention;
use App\Models\Stat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateStatsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function handle()
    {
        // Calculate periods
        $now = now();
        $startOfDay = $now->copy()->startOfDay();
        $startOfWeek = $now->copy()->startOfWeek();
        $startOfMonth = $now->copy()->startOfMonth();

        // Query all instruments
        $instruments = Instrument::all(['id']);

        foreach ($instruments as $instrument) {
            $instrumentId = $instrument->id;

            // Count mentions for each period
            $dailyCount = $this->countMentions($instrumentId, $startOfDay);
            $weeklyCount = $this->countMentions($instrumentId, $startOfWeek);
            $monthlyCount = $this->countMentions($instrumentId, $startOfMonth);

            // Update or insert into stats table
            Stat::updateOrCreate(
                ['instrument_id' => $instrumentId],
                [
                    'mentions_daily' => $dailyCount,
                    'mentions_weekly' => $weeklyCount,
                    'mentions_monthly' => $monthlyCount,
                    'last_updated' => $now
                ]
            );
        }
    }

    private function countMentions($instrumentId, $start)
    {
        return InstrumentMention::where('instrument_id', $instrumentId)->where('mentioned_at', '>=', $start)->count();
    }
}
