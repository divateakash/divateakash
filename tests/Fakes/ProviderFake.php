<?php

namespace Tests\Fakes;

use App\Messaging\ProviderMessage;
use App\Messaging\Providers\Provider;
use Illuminate\Support\Str;

class ProviderFake extends Provider
{
    public $messages = [];

    public function send($user, $password, $from, $to, $message)
    {
        $this->messages[] = [
            'user' => $user,
            'password' => $password,
            'from' => number($from),
            'to' => number($to),
            'message' => $message,
        ];

        return new ProviderMessage(Str::uuid(), $from, $to, $message);
    }

    public function assertMessageSentTo($number)
    {
        return collect($this->messages)->where('to', $number)->count() > 0;
    }

    public function sentMessages()
    {
        return collect($this->messages);
    }
}
