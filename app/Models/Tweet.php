<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tweet extends Model
{
    protected $fillable = ['handle_id', 'tweet_id', 'content', 'processed'];

    public function handle(): BelongsTo
    {
        return $this->belongsTo(Handle::class);
    }

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'instrument_mentions');
    }
}
