<?php

namespace App\Jobs;

use App\Outbound;
use App\Support\CarrierLookup;
use App\Suppression;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class AddLeadToCatalog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $catalog;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($catalog, $data)
    {
        $this->catalog = $catalog;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lead = $this->catalog->leads()->firstOrNew([
            'phone' => number($this->data['phone']),
        ], [
            'first_name' => $this->data['first_name'] ?? null,
            'last_name' => $this->data['last_name'] ?? null,
            'email' => $this->data['email'] ?? null,
            'team_id' => $this->catalog->team_id,
        ]);

        if ($lead->isSuppressed() || Suppression::isSuppressed($lead->team_id, $lead->phone)) {
            return;
        }

        $carrierInformation = CarrierLookup::phone($lead->phone);

        if (!$carrierInformation->mobile()) {
            return;
        }

        $lead->fillWithCarrierInformation($carrierInformation)
            ->save();

        foreach ($this->catalog->activeDrips()->get() as $campaign) {
            if ($campaign->allowsCarrier($lead->carrier_id)) {
                $this->createPendingOutbound($lead, $campaign);
            }
        }
    }

    public function createPendingOutbound($lead, $campaign)
    {
        $hash = Str::random(6);

        $link = $campaign->getLink();
        $sendAt = $campaign->sendDripMessageAt();
        if ($campaign->drip_time_limit) {
            if ($this->overTimeLimit($lead, $sendAt, $campaign->drip_time_limit)) {
                $sendAt = $sendAt->addDay()->hour($lead->globalStartHour());
            }
        }

        if ($sendAt->hour) {
            return Outbound::create([
                'to' => number($lead->phone),
                'cost' => $campaign->account->sending_price,
                'hash' => $hash,
                'link' => $link,
                'processed' => false,
                'send_at' => $sendAt,
                'campaign_id' => $campaign->id,
                'offer_id' => $campaign->offer_id,
                'lead_id' => $lead->id,
                'account_id' => $campaign->account_id,
                'message_group_id' => $campaign->message_group_id,
                'team_id' => $campaign->team_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function overTimeLimit($lead, Carbon $time, $timeLimit)
    {
        $localTime = $lead->toLocalTime($time);

        [$hour, $minute] = explode(':', $timeLimit);

        $localTimeLimit = $localTime->copy()->hour($hour)->minute($minute);

        return $localTime->greaterThan($localTimeLimit);
    }
}
