<?php

namespace App\Jobs\Sending;

use App\Outbound;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class GenerateOutboundsFromLeads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    public $leads;

    public $campaign;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($leads, $campaign)
    {
        $this->leads = $leads;
        $this->campaign = $campaign;
        $this->onQueue('generate-outbounds-from-leads');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->createOutbounds($this->leads);
    }

    public function createOutbounds($leads)
    {
        $outbounds = $leads->map(fn($lead) => $this->createPendingOutbound($lead));

        Outbound::insert($outbounds->all());

        $this->campaign->update(['status' => 'paused']);
    }

    public function createPendingOutbound($lead)
    {
        $hash = $this->campaign->usesHash() ? Str::random(6) : null;
        $domainGroupId = $this->campaign->usesAmazonLinks() ? null : $this->campaign->domain_group_id;
        $link = $this->campaign->getLink();

        return [
            'to' => number($lead->phone),
            'cost' => $this->campaign->account->sending_price,
            'hash' => $hash,
            'link' => $link,
            'processed' => false,
            'send_at' => now(),
            'campaign_id' => $this->campaign->id,
            'offer_id' => $this->campaign->offer_id,
            'lead_id' => $lead->id,
            'account_id' => $this->campaign->account_id,
            'message_group_id' => $this->campaign->message_group_id,
            'domain_group_id' => $domainGroupId,
            'team_id' => $this->campaign->team_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function tags()
    {
        return ['GenerateOutboundsFromLeads'];
    }
}
