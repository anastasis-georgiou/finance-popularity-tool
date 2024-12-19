<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Instrument extends Model
{
    protected $fillable = ['symbol'];

    public function tweets(): BelongsToMany
    {
        return $this->belongsToMany(Tweet::class, 'instrument_mentions');
    }
}
