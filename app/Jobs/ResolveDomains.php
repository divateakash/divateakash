<?php

namespace App\Jobs;

use App\Domain;
use App\Jobs\ResolveDomain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResolveDomains implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $domains = Domain::where('status', '!=', 'deleted')->get();

        foreach ($domains as $domain) {
            dispatch(new ResolveDomain($domain));
        }
    }

    public function tags()
    {
        return ['ResolveDomains'];
    }
}
