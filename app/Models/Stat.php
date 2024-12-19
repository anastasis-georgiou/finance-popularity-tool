<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stat extends Model
{
    protected $fillable = ['instrument_id', 'mentions_daily', 'mentions_weekly', 'mentions_monthly'];

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
