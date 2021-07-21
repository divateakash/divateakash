<?php

namespace App\Messaging\Providers;

use App\DeliveryReport;
use App\Messaging\Exceptions\MessageFailedToSendException;
use App\Messaging\ProviderMessage;
use App\Messaging\Providers\Provider;
use App\Outbound;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Gorilla extends Provider
{
    public $endpoint = 'https://api.smsgorilla.com';

    public $callbackUrl;

    public function __construct()
    {
        $this->callbackUrl = route('providers.delivery_report', 'gorilla');
    }

    public function send($user, $password, $from, $to, $message)
    {
        $response = Http::withBasicAuth($user, $password)
            ->post($this->endpoint('messages'), [
                'from' => number($from),
                'to' => number($to),
                'body' => $message,
                'callbackURL' => $this->callbackUrl,
            ]);

        if (!$response->ok()) {
            Log::error('Message failed to send on the Gorilla provider.', [
                'from' => $this->formatFromNumber($from),
                'user' => $user,
                'password' => $password,
                'error' => $response->body(),
            ]);

            $content = $response->json()['message'] ?? 'Error unavailable';

            throw new MessageFailedToSendException($content);
        }

        return ProviderMessage::fromGorilla($response->json());
    }

    public function deliveryReportFromRequest(array $fields)
    {
        $outbound = Outbound::where('response', $fields['id'])->latest('id')->first();

        if (!$outbound) {
            return null;
        }

        $meta = Arr::only($fields, ['id', 'action', 'detail', 'reason']);
        // $error = $this->parseDlrError($fields['detail']);

        $deliveryReport = DeliveryReport::make([
            'status' => $this->parseDlrStatus($fields['detail']),
            'delivered_at' => now(),
            'error' => null,
            'outbound_id' => $outbound->id,
        ]);

        return tap($deliveryReport, fn($deliveryReport) => $deliveryReport->meta = $meta);
    }

    public function parseDlrStatus(string $status)
    {
        return [
            'DELIVERED' => 'Delivered',
            'REJECTED' => 'Rejected',
            'UNDELIVERABLE' => 'Undeliverable',
        ][$status] ?? $status;
    }

    public function parseDlrError($error)
    {
        $errors = [
            '0' => null,
            '00' => null,
            '000' => null,
            '11' => 'number_does_not_exist',
            '20' => 'queue_is_full',
            '112' => 'spam',
        ];

        if (array_key_exists($error, $errors)) {
            return $errors[$error];
        }

        return null;
    }
}
