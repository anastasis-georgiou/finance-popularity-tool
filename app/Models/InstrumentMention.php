<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstrumentMention extends Model
{
    protected $fillable = ['instrument_id', 'tweet_id', 'mentioned_at'];
}
