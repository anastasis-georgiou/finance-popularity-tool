<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instrument extends Model
{
    use HasFactory;
    protected $fillable = ['symbol'];

    public function tweets(): BelongsToMany
    {
        return $this->belongsToMany(Tweet::class, 'instrument_mentions');
    }

    public function mentions(): HasMany
    {
        return $this->hasMany(InstrumentMention::class, 'instrument_id');
    }

}
