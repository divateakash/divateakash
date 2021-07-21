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
use Illuminate\Support\Str;

class SimpleTexting extends Provider
{
    public $endpoint = 'https://app2.simpletexting.com/v1';

    public $callbackUrl;

    public function __construct()
    {
        $this->callbackUrl = route('providers.delivery_report', 'simpletexting');
    }

    public function send($user, $password, $from, $to, $message)
    {
        $to = local_number($to);

        $response = Http::asJson()
            ->post($this->endpoint("send?token={$user}&phone={$to}&message={$message}"));

        $errored = !$response->ok() || !Str::contains($response->body(), '<code>1</code>');
        $data = (array) simplexml_load_string($response->body());

        if ($errored) {
            Log::error('Message failed to send on the SimpleTexting provider.', [
                'from' => $this->formatFromNumber($from),
                'user' => $user,
                'password' => $password,
                'error' => $response->body(),
            ]);

            $content = $data['message'] ?? 'Error unavailable';

            throw new MessageFailedToSendException($content);
        }

        return ProviderMessage::fromSimpleTexting($data['smsid'], $from, $to, $message);
    }

    public function deliveryReportFromRequest(array $fields)
    {
        $outbound = Outbound::where('response', $fields['id'])->latest('id')->first();

        if (!$outbound) {
            return null;
        }

        $meta = Arr::only($fields, ['id', 'status', 'carrier']);

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
            'delivered' => 'Delivered',
            'undelivered' => 'Undeliverable',
        ][$status] ?? $status;
    }
}
