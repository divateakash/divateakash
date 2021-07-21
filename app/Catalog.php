<?php

namespace App;

use App\Campaign;
use App\Lead;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Catalog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'uuid',
        'team_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'team_id' => 'integer',
    ];

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function suppressedLeads()
    {
        return $this->hasMany(Lead::class)->suppressed();
    }

    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class);
    }

    public function amountToSend($carriers = [])
    {
        return $this
            ->leads()
            ->active()
            ->when(count($carriers), fn($query) => $query->whereIn('carrier_id', $carriers->map->id))
            ->count();
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function activeDrips()
    {
        return $this->campaigns()->sending()->drip();
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
