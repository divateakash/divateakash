<?php

namespace App;

use App\Services\NamecheapManager;
use Illuminate\Database\Eloquent\Model;

class DomainProvider extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user',
        'password',
        'provider',
        'team_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
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

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function namecheapManager()
    {
        return new NamecheapManager($this->user, $this->password);
    }
}
