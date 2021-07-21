<?php

namespace App\Messaging\Providers;

use App\DeliveryReport;
use App\Messaging\Exceptions\MessageFailedToSendException;
use App\Messaging\ProviderMessage;
use App\Messaging\Providers\Provider;
use App\Outbound;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class Twilio extends Provider
{
    public $endpoint = 'https://api.twilio.com';

    public $callbackUrl;

    public function __construct()
    {
        $this->callbackUrl = route('providers.delivery_report', 'twilio');
    }

    public function send($user, $password, $from, $to, $message)
    {
        $twilio = new Client($user, $password);

        try {
            $message = $twilio->messages
                ->create($to,
                    [
                        "from" => $from,
                        "body" => $message,
                        'statusCallback' => $this->callbackUrl,
                    ]
                );
        } catch (\Exception $exception) {
            throw new MessageFailedToSendException($exception->getMessage());
        }

        if ($message->errorMessage) {
            Log::error('Message failed to send on the Twilio provider.', [
                'from' => $this->formatFromNumber($from),
                'user' => $user,
                'password' => $password,
                'error' => $message->errorMessage,
            ]);

            throw new MessageFailedToSendException($message->errorMessage ?? null);
        }

        return ProviderMessage::fromTwilio($message);
    }

    public function deliveryReportFromRequest(array $fields)
    {
        if (!in_array($fields['SmsStatus'], ['delivered', 'undelivered', 'failed'])) {
            return null;
        }

        $outbound = Outbound::where('response', $fields['SmsSid'])->latest('id')->first();

        if (!$outbound) {
            return null;
        }

        $meta = Arr::only($fields, ['SmsStatus', 'SmsSid']);

        $deliveryReport = DeliveryReport::make([
            'status' => $this->parseDlrStatus($fields['SmsStatus']),
            'delivered_at' => now(),
            'error' => null,
            'outbound_id' => $outbound->id,
        ]);

        return tap($deliveryReport, fn($deliveryReport) => $deliveryReport->meta = $meta);
    }

    public function parseDlrStatus(string $status)
    {
        return [
            'delivered' => 'Delivered',
            'undelivered' => 'Rejected',
            'failed' => 'Rejected',
        ][$status] ?? $status;
    }
}
