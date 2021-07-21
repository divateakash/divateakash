<?php

namespace App\Jobs;

use App\Jobs\UpdateDomainDNS;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResolveDomain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $domain;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($domain)
    {
        $this->onQueue('update-dns');
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $host = gethostbyname($this->domain->domain);

        if ($host === config('konnectext.domains_ip')) {
            // The domain is resolving properly.

            return $this->domain->setAsActive();
        }

        $this->domain->setAsResolving();

        dispatch(new UpdateDomainDNS($this->domain));
    }

    public function tags()
    {
        return ['ResolveDomain', 'domain:' . $this->domain->domain];
    }
}
