<?php

namespace App\Jobs;

use App\Domain;
use App\Jobs\UpdateDomainDNS;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use League\Csv\Reader;

class ImportDomain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $domainGroup;

    public $domain;

    public $dispatch = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($domainGroup, $domain, $dispatch = false)
    {
        $this->domainGroup = $domainGroup;
        $this->domain = $domain;
        $this->dispatch = $dispatch;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->dispatch) {
            return $this->dispatchJobs();
        }

        $domain = Domain::create([
            'domain' => $this->domain,
            'status' => 'resolving',
            'points_to' => null,
            'dns_last_updated_at' => null,
            'expires_at' => null,
            'domain_group_id' => $this->domainGroup->id,
            'domain_provider_id' => $this->domainGroup->domain_provider_id,
            'team_id' => $this->domainGroup->team_id,
        ]);

        UpdateDomainDNS::dispatch($domain);
    }

    public function dispatchJobs()
    {
        $csv = Reader::createFromString($this->domain);
        foreach ($csv->getRecords() as $record) {
            self::dispatch($this->domainGroup, Arr::wrap($record)[0]);
        }
    }

    public function tags()
    {
        if ($this->dispatch) {
            return ['DispatchImportDomains'];
        }

        return [
            'ImportDomains',
            'domain:' . $this->domain,
        ];
    }
}
