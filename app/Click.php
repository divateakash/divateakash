<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot',
        'offer_id',
        'domain_id',
        'message_id',
        'number_id',
        'campaign_id',
        'outbound_id',
        'team_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot' => 'boolean',
        'offer_id' => 'integer',
        'campaign_id' => 'integer',
        'outbound_id' => 'integer',
        'number_id' => 'integer',
        'message_id' => 'integer',
        'domain_id' => 'integer',
        'team_id' => 'integer',
    ];

    public function offer()
    {
        return $this->belongsTo(\App\Offer::class);
    }

    public function campaign()
    {
        return $this->belongsTo(\App\Campaign::class);
    }

    public function outbound()
    {
        return $this->belongsTo(\App\Outbound::class);
    }

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function scopeValid($query)
    {
        return $query->where('bot', false);
    }
}
