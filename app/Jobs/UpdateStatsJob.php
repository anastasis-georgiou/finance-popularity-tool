<?php

namespace App\Jobs;

use App\Models\Instrument;
use App\Models\InstrumentMention;
use App\Models\Stat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStatsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function handle()
    {
        foreach (Instrument::all(['id']) as $instrument) {
            // Count mentions for each period
            $dailyCount = $this->countMentions($instrument->id, now()->startOfDay());
            $weeklyCount = $this->countMentions($instrument->id, now()->startOfWeek());
            $monthlyCount = $this->countMentions($instrument->id, now()->startOfMonth());

            // Update or insert into stats table
            Stat::updateOrCreate(
                ['instrument_id' => $instrument->id],
                [
                    'mentions_daily' => $dailyCount,
                    'mentions_weekly' => $weeklyCount,
                    'mentions_monthly' => $monthlyCount,
                    'last_updated' => now()
                ]
            );
        }
    }

    private function countMentions($instrumentId, $start)
    {
        return InstrumentMention::where('instrument_id', $instrumentId)->where('mentioned_at', '>=', $start)->count();
    }
}
