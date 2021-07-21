<?php

namespace App\Jobs;

use App\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinishCampaigns implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $campaigns = Campaign::regular()->whereIn('status', ['paused', 'sending'])->get();

        foreach ($campaigns as $campaign) {
            if ($campaign->created_at->lessThan(now()->subMinutes(5)) && $campaign->outbounds()->pending()->count() == 0) {
                $campaign->update(['status' => 'finished']);
            }
        }
    }
}
