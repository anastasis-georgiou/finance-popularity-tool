<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentMention extends Model
{
    use HasFactory;
    protected $fillable = ['instrument_id', 'tweet_id', 'mentioned_at'];
}
