<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use League\Csv\Reader;

class ImportNumber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;

    public $number;

    public $dispatch;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account, $number, $dispatch = false)
    {
        $this->onQueue('import-numbers');
        $this->account = $account;
        $this->number = $number;
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

        $number = $this->parseNumber();

        $this->account->numbers()->firstOrCreate([
            'number' => $number,
            'status' => 'active',
            'provider_id' => $this->account->provider_id,
        ]);
    }

    public function parseNumber()
    {
        if (is_array($this->number)) {
            $this->number = Arr::first($this->number);
        }

        $this->number = preg_replace('/[^0-9]/', '', $this->number);

        return number($this->number);
    }

    public function dispatchJobs()
    {
        $csv = Reader::createFromString($this->number);

        foreach ($csv->getRecords() as $record) {
            self::dispatch($this->account, $record);
        }
    }

    public function tags()
    {
        if ($this->dispatch) {
            return ['ImportNumbers'];
        }

        return [
            'ImportNumber', 'number:' . $this->parseNumber(),
        ];
    }
}
