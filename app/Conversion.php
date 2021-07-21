<?php

namespace App;

use App\Campaign;
use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    protected $fillable = [
        'click_id',
        'client_id',
        'cost',
        'revenue',
        'profit',
        'campaign_id',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
