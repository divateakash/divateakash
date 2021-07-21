<?php

namespace App\Http\Controllers\Providers;

use App\Http\Controllers\Controller;
use App\Lead;
use App\Messaging\Providers\Provider;
use App\Messaging\Providers\Txtria;

class DeliveryReportsController extends Controller
{
    public function __invoke($provider)
    {
        if (env('LOG_REQUESTS')) {
            logger(request()->all());
        }

        $provider = Provider::factory($provider);

        $deliveryReport = $provider->deliveryReportFromRequest(request()->all());
        abort_if(is_null($deliveryReport), 404);

        $deliveryReport->save();

        $this->handleErrors($deliveryReport, $provider);

        return response()->json([
            'success' => true,
        ]);
    }

    public function handleErrors($deliveryReport, $provider)
    {
        if ($deliveryReport->spam() && $provider instanceof Txtria) {
            optional($deliveryReport->outbound->message)->deleteWithReason('Spam - ' . $deliveryReport->meta['error']);

            $this->detachAccountIfNeeded($deliveryReport);

        } elseif ($deliveryReport->invalidNumber()) {
            Lead::where('phone', $deliveryReport->outbound->to)->update([
                'suppressed_at' => now(),
            ]);
        }
    }

    public function detachAccountIfNeeded($deliveryReport)
    {
        if (
            $deliveryReport->outbound->isReply() &&
            $deliveryReport->outbound->campaign->replyAccount->is_group
        ) {
            $deliveryReport->outbound->campaign->replyAccount->detachAccount(
                $deliveryReport->outbound->account,
                "Spam - {$deliveryReport->meta['error']}"
            );
        }
    }
}
