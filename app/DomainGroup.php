<?php

namespace App;

use App\Domain;
use Illuminate\Database\Eloquent\Model;

class DomainGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'domain_provider_id',
        'team_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'minimum_amount' => 'integer',
        'maximum_amount' => 'integer',
        'maximum_sends' => 'integer',
        'domain_provider_id' => 'integer',
        'team_id' => 'integer',
    ];

    public function domainProvider()
    {
        return $this->belongsTo(\App\DomainProvider::class);
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function activeDomains()
    {
        return $this->domains()->active();
    }

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }
}
