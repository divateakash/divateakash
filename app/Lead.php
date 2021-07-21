<?php

namespace App;

use App\Support\CarrierLookup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'timezone',
        'region',
        'city',
        'start_hour',
        'end_hour',
        'carrier',
        'carrier_id',
        'type',
        'suppressed_at',
        'catalog_id',
        'team_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'catalog_id' => 'integer',
        'team_id' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'suppressed_at',
    ];

    public function catalog()
    {
        return $this->belongsTo(\App\Catalog::class);
    }

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('suppressed_at');
    }

    public function scopeSuppressed($query)
    {
        return $query->whereNotNull('suppressed_at');
    }

    public function isSuppressed()
    {
        return !is_null($this->suppressed_at);
    }

    public function suppress()
    {
        $this->update(['suppressed_at' => now()]);
    }

    public function globallySuppress()
    {
        self::where('team_id', $this->team_id)->where('phone', $this->phone)->update([
            'suppressed_at' => now(),
        ]);
    }

    public function toLocalTime(Carbon $carbon)
    {
        return $carbon->copy()->timezone($this->timezone);
    }

    public function localNow()
    {
        return now()->copy()->timezone($this->timezone);
    }

    public function localStartHour()
    {
        return today()->copy()->timezone($this->timezone)->hour(8)->hour;
    }

    public function localEndHour()
    {
        return today()->copy()->timezone($this->timezone)->hour(21)->hour;
    }

    public function globalStartHour()
    {
        return today()->copy()->timezone($this->timezone)->hour(8)->timezone('UTC')->hour;
    }

    public function underSendHours()
    {
        return ($this->localNow()->hour >= $this->localStartHour() && $this->localNow()->hour < $this->localEndHour());

        return ($time->hour >= $this->startHour()) && ($time->hour < $this->endHour());
    }

    public function nextStartHour()
    {
        // if the outbound is scheduled to be sent before the sending
        // hour starts we schedule to it to the same day.
        if ($this->localNow()->hour < $this->localStartHour()) {
            return $this->localNow()->hour(8)->timezone('UTC');
        }

        // if the outbound is scheduled to be sent after the end hours,
        // we schedule it to be sent at the start hour of the next day.
        return $this->localNow()->addDay()->hour(8)->timezone('UTC');
    }

    public function fillWithCarrierInformation(CarrierLookup $information)
    {
        [$startHour, $endHour] = $information->sendingHours();

        $this->city = $information->city;
        $this->region = $information->region;
        $this->timezone = $information->timezone;
        $this->carrier = $information->carrier;
        $this->carrier_id = $information->carrierObject()->id;
        $this->type = $information->type;
        $this->start_hour = $startHour;
        $this->end_hour = $endHour;

        return $this;
    }
}
