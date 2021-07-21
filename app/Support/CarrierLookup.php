<?php

namespace App\Support;

use App\Carrier;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarrierLookup
{
    public $timezone;

    public $region;

    public $carrier;

    public $type;

    public $mainCarriers = [
        'Verizon', 'T-Mobile', 'AT&T', 'MetroPCS', 'U.S Cellular', 'Sprint',
    ];

    public function __construct($timezone, $region, $city, $carrier, $type)
    {
        $this->timezone = $timezone;
        $this->region = $region;
        $this->city = $city;
        $this->carrier = $carrier;
        $this->type = $type;
    }

    public static function phone($phone)
    {
        $phone = local_number($phone);

        $data = self::fetchData($phone);

        if (!$data) {
            return new static(null, null, null, null, null);
        }

        return new static($data['timezone'], $data['region'], ucwords(Str::lower($data['city'])), $data['carrier'], $data['type']);
    }

    public function mobile()
    {
        return $this->type === 'C';
    }

    public function sendingHours()
    {
        $start = (int) today()->copy()->timezone($this->timezone)->hour(8)->timezone(config('app.timezone'))->format('H');
        $end = (int) today()->copy()->timezone($this->timezone)->hour(21)->timezone(config('app.timezone'))->format('H');

        return [$start, $end];
    }

    public function carrierObject()
    {
        $carrier = Str::before($this->carrier, ' ');

        if (in_array($carrier, $this->mainCarriers)) {
            return Carrier::where('name', $carrier)->firstOr(function () {
                return Carrier::where('name', 'Others')->first();
            });
        }

        return Carrier::where('name', 'Others')->first();
    }

    public static function fetchData($phone)
    {
        $npa = substr($phone, 0, 3);
        $nxx = substr($phone, 3, 3);
        $block = substr($phone, 6, 1);

        return Cache::remember("{$npa}.{$nxx}.{$block}_lookup", now()->addHour(), function () use ($npa, $nxx, $block) {
            $data = DB::table('phones')
                ->where('NPA', $npa)
                ->where('NXX', $nxx)
                ->where('BLOCK_ID', $block)
                ->join('ocn', 'phones.ocn', '=', 'ocn.OCN')
                ->select('phones.*', 'ocn.OCN', 'ocn.CommonName')
                ->first();

            if (!$data) {
                $data = DB::table('phones')
                    ->where('NPA', $npa)
                    ->where('NXX', $nxx)
                    ->join('ocn', 'phones.ocn', '=', 'ocn.OCN')
                    ->select('phones.*', 'ocn.OCN', 'ocn.CommonName')
                    ->first();
            }

            if (!$data) {
                return;
            }

            return [
                'carrier' => $data->CommonName,
                'type' => $data->LTYPE,
                'region' => $data->STATE,
                'city' => $data->WC,
                'timezone' => $data->OLSON,
            ];

        });
    }
}
