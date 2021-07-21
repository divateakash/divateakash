<?php

namespace App\Messaging\Providers;

use App\Messaging\Providers\Gorilla;
use App\Messaging\Providers\SimpleTexting;
use App\Messaging\Providers\TextCalibur;
use App\Messaging\Providers\Twilio;
use App\Messaging\Providers\Txtria;
use Tests\Fakes\ProviderFake;

class Provider
{
    public static $providers = [
        'gorilla' => Gorilla::class,
        'txtria' => Txtria::class,
        'twilio' => Twilio::class,
        'textcalibur' => TextCalibur::class,
        'simpletexting' => SimpleTexting::class,
    ];

    public static $fake = false;

    public static function factory($provider)
    {
        if (static::$fake) {
            return static::$fake;
        }

        return resolve(static::$providers[$provider]);
    }

    public static function fake()
    {
        static::$fake = new ProviderFake;

        return static::$fake;
    }

    public static function exists($provider)
    {
        return array_key_exists($provider, static::$providers);
    }

    public function endpoint($endpoint)
    {
        return $this->endpoint . '/' . $endpoint;
    }

    public function formatFromNumber($number)
    {
        return $number;
    }

    public function formatToNumber($number)
    {
        return $number;
    }

    public static function restore()
    {
        self::$fake = false;
    }
}
