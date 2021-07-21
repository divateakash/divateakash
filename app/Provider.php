<?php

namespace App;

use App\Account;
use App\Number;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'provider',
        'username',
        'password',
        'cost',
        'type',
        'team_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'cost' => 'float',
        'team_id' => 'integer',
    ];

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function numbers()
    {
        return $this->hasMany(Number::class);
    }

    public function reserveNumbers()
    {
        return $this->hasMany(Number::class)->whereNull('account_id');
    }

    public function getParsedProviderAttribute()
    {
        return [
            'gorilla' => 'SMS Gorilla',
            'txtria' => 'TxTRIA',
            'twilio' => 'Twilio',
            'textcalibur' => 'Text Calibur',
            'simpletexting' => 'Simple Texting',
        ][$this->provider] ?? $this->provider;
    }
}
