<?php

namespace App\Jobs;

use App\Exceptions\FailedToUpdateDomainDnsException;
use App\Services\NamecheapManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateDomainDNS implements ShouldQueue
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
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            (new NamecheapManager(
                $this->domain->domainProvider->user,
                $this->domain->domainProvider->password
            ))->updateDNS($this->domain);
        } catch (FailedToUpdateDomainDnsException $exception) {
            return $this->domain->update([
                'errored_at' => now(),
                'error' => $exception->getMessage(),
                'status' => 'errored',
            ]);
        }

        $this->domain->update([
            'status' => 'resolving',
            'dns_last_updated_at' => now(),
            'points_to' => config('konnectext.domains_ip'),
            'error' => null,
            'errored_at' => null,
        ]);
    }

    public function tags()
    {
        return [
            'UpdateDomainDNS',
            'domain:' . $this->domain->domain,
        ];
    }
}
