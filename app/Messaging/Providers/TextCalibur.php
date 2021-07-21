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

class TextCalibur extends Provider
{
    public $endpoint = 'https://api.text-calibur.com/v1';

    public $callbackUrl;

    public function __construct()
    {
        $this->callbackUrl = route('providers.delivery_report', 'textcalibur');
    }

    public function send($user, $password, $from, $to, $message)
    {
        $response = Http::withBasicAuth($user, $password ?? '')
            ->post($this->endpoint('sms/send'), [
                'from' => number($from),
                'to' => number($to),
                'body' => $message,
                'status_callback' => $this->callbackUrl,
            ]);

        if (!$response->ok()) {
            Log::error('Message failed to send on the TextCalibur provider.', [
                'from' => $this->formatFromNumber($from),
                'user' => $user,
                'password' => $password,
                'error' => $response->body(),
            ]);

            $content = Arr::wrap($response->json()['message'])[0] ?? 'Error unavailable';

            throw new MessageFailedToSendException($content);
        }

        $data = $response->json();

        return ProviderMessage::fromTextCalibur($data['sid'][0], $from, $to, $message);
    }

    public function deliveryReportFromRequest(array $fields)
    {
        $outbound = Outbound::where('response', $fields['sid'])->latest('id')->first();

        if (!$outbound) {
            return null;
        }

        $meta = Arr::only($fields, ['sid', 'status', 'error_code', 'delivered_at']);
        // $error = $this->parseDlrError($fields['detail']);

        $deliveryReport = DeliveryReport::make([
            'status' => $this->parseDlrStatus($fields['status']),
            'delivered_at' => now(),
            'error' => null,
            'outbound_id' => $outbound->id,
        ]);

        return tap($deliveryReport, fn($deliveryReport) => $deliveryReport->meta = $meta);
    }

    public function parseDlrStatus(string $status)
    {
        return [
            'sent' => 'Sent',
            'delivered' => 'Delivered',
            'undelivered' => 'Undeliverable',
            'failed' => 'Rejected',
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
