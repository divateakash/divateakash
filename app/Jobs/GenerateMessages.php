<?php

namespace App\Jobs;

ini_set('memory_limit', '2048M');

use App\Jobs\Sending\CreatePendingOutboundFromLead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;

    public $timeout = 1200;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign, $filters = [])
    {
        $this->campaign = $campaign;
        $this->onQueue('generate-messages');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $limit = $this->campaign->limit ?: 10000000;

        $leads = $this->campaign->catalog
            ->leads()
            ->active()
            ->take($limit)
            ->skip($this->campaign->skip)
            ->when($this->campaign->hasLimitedCarriers(), fn($query) => $query->whereIn('carrier_id', $this->campaign->carriers))
            ->get(['id', 'phone']);

        $this->campaign->update(['status' => 'paused']);

        foreach ($leads as $lead) {
            CreatePendingOutboundFromLead::dispatch(
                $lead,
                $this->campaign,
                $this->campaign->account->sending_price,
                $this->campaign->getLink(),
            );
            // GenerateOutboundsFromLeads::dispatch($leads, $this->campaign);
        }

        // $this->campaign->update(['status' => 'waiting']);
    }

    public function tags()
    {
        return ['GenerateMessages'];
    }
}
