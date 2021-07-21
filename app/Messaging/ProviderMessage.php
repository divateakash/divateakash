<?php

namespace App\Messaging;

use Twilio\Rest\Api\V2010\Account\MessageInstance;

class ProviderMessage
{
    public $id;

    public $from;

    public $to;

    public $message;

    public $balance;

    public function __construct($id, $from, $to, $message, $balance = null)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->message = $message;
        $this->balance = $balance;
    }

    public static function fromGorilla($message)
    {
        $message = $message['result'];

        return new static(
            $message['id'], number($message['from']), number($message['to']), $message['body'], $message['balance']
        );
    }

    public static function fromSevenG($message, $from, $to, $body)
    {
        $id = $message['request_id'] ?? null;

        return new static($id, e164($from), e164($to), $body);
    }

    public static function fromTxtria($id, $from, $to, $body)
    {
        return new static($id, e164($from), e164($to), $body);
    }

    public static function fromTwilio(MessageInstance $message)
    {
        return new static($message->sid, e164($message->from), e164($message->to), $message->body);
    }

    public static function fromTextCalibur($id, $from, $to, $body)
    {
        return new static($id, e164($from), e164($to), $body);
    }

    public static function fromSimpleTexting($id, $from, $to, $body)
    {
        return new static($id, e164($from), e164($to), $body);
    }
}
