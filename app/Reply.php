<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'from',
        'to',
        'outbound_id',
        'campaign_id',
        'account_id',
        'team_id',
        'stop',
        'good',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'stop' => 'boolean',
        'good' => 'boolean',
        'outbound_id' => 'integer',
        'reply_outbound_id' => 'integer',
        'campaign_id' => 'integer',
        'team_id' => 'integer',
    ];

    public function outbound()
    {
        return $this->belongsTo(\App\Outbound::class);
    }

    public function repliedOutbounds()
    {
        return $this->hasMany(\App\Outbound::class);
    }

    public function campaign()
    {
        return $this->belongsTo(\App\Campaign::class);
    }

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function scopeGood($query)
    {
        return $query->where('stop', false)->where('good', true);
    }

    public function scopeBad($query)
    {
        return $query->where('stop', true);
    }

    public function scopeAfter($query, $after)
    {
        return $query->where('created_at', '>', $after);
    }

    public function isGood()
    {
        return ($this->good == true || is_null($this->good)) && !$this->isBad();
    }

    public function isBad()
    {
        return $this->stop == true;
    }
}
