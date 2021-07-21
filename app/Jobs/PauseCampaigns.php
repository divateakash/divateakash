<?php

namespace App\Jobs;

use App\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PauseCampaigns implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $campaigns = Campaign::sending()->whereHas('domainGroup', fn($query) =>
            $query->whereDoesntHave('domains', fn($subQuery) => $subQuery->active())
        )->get();

        $campaigns->each->pause();
    }

    public function tags()
    {
        return ['PauseCampaigns'];
    }
}
