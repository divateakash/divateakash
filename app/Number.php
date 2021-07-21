<?php

namespace App;

use App\Outbound;
use App\Reply;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Number extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'status',
        'provider_id',
        'account_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'provider_id' => 'integer',
        'account_id' => 'integer',
    ];

    public function provider()
    {
        return $this->belongsTo(\App\Provider::class);
    }

    public function account()
    {
        return $this->belongsTo(\App\Account::class);
    }

    public function outbounds()
    {
        return $this->hasMany(Outbound::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'to', 'number');
    }

    public function badReplies()
    {
        return $this->replies()->bad();
    }

    public function getParsedStatusAttribute()
    {
        return Str::camel($this->status);
    }

    public function averageOptOut($timeframe = 24)
    {
        $after = now()->subHours($timeframe);

        $totalReplies = $this->replies_count ?? $this->replies()->after($after)->count();
        $badReplies = $this->bad_replies_count ?? $this->badReplies()->after($after)->count();

        return (float) $badReplies / ($totalReplies ?: 1);
    }
}
