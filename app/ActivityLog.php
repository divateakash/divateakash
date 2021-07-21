<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'content', 'model_id', 'model_type',
    ];

    public function model()
    {
        return $this->morphTo('model');
    }
}
