<?php

namespace App;

use App\Outbound;
use Illuminate\Database\Eloquent\Model;

class DeliveryReport extends Model
{
    protected $fillable = [
        'status', 'delivered_at', 'error', 'meta', 'outbound_id',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    const DELIVERED = 'Delivered';
    const REJECTED = 'Rejected';

    public function outbound()
    {
        return $this->belongsTo(Outbound::class);
    }

    public function delivered()
    {
        return $this->status === 'Delivered';
    }

    public function spam()
    {
        return ($this->error === 'spam') || ($this->error == 69);
    }

    public function invalidNumber()
    {
        return $this->error === 'number_does_not_exist';
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::DELIVERED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::REJECTED);
    }
}
