<?php

namespace App;

use App\Outbound;
use App\Services\NamecheapManager;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain',
        'status',
        'points_to',
        'dns_last_updated_at',
        'expires_at',
        'domain_group_id',
        'domain_provider_id',
        'error',
        'errored_at',
        'team_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'domain_group_id' => 'integer',
        'domain_provider_id' => 'integer',
        'team_id' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'dns_last_updated_at',
        'expires_at',
        'errored_at',
    ];

    public function domainGroup()
    {
        return $this->belongsTo(\App\DomainGroup::class);
    }

    public function domainProvider()
    {
        return $this->belongsTo(\App\DomainProvider::class);
    }

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function outbounds()
    {
        return $this->hasMany(Outbound::class);
    }

    public function sld()
    {
        return explode('.', $this->domain)[0];
    }

    public function tld()
    {
        return explode('.', $this->domain)[1];
    }

    public function updateDNS()
    {
        return $this->domainProvider->namecheapManager()->updateDNS($this);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function setAsActive()
    {
        $this->update([
            'status' => 'active',
            'error' => null,
            'errored_at' => null,
            'points_to' => config('konnectext.domains_ip'),
        ]);
    }

    public function setAsResolving()
    {
        $this->update([
            'status' => 'resolving',
        ]);
    }
}
