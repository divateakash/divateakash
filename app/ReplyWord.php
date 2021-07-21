<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReplyWord extends Model
{
    const GOOD = 'good';
    const BAD = 'bad';

    protected $fillable = [
        'word', 'type', 'team_id',
    ];

    public function scopeGood($query)
    {
        return $query->where('type', self::GOOD);
    }

    public function scopeBad($query)
    {
        return $query->where('type', self::BAD);
    }
}
