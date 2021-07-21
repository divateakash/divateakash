<?php

namespace App\Jobs;

ini_set('memory_limit', '2048M');

use App\FamFile;
use App\Support\CarrierLookup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Writer;

class ProcessFamLeads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;

    public $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, $name)
    {
        $this->path = $path;
        $this->name = $name;
        $this->onQueue('process-fam-leads');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logger('Starting FAM leads');

        $file = Storage::get($this->path);
        $csv = Reader::createFromString($file);

        $writer = Writer::createFromString();

        logger('Starting iteration');

        foreach ($csv->getRecords() as $key => $record) {
            if ($key < 100) {
                logger('Logging  ' . $key);
            }

            if (str_contains($key, '0000')) {
                logger('Logging . ' . $key);
            }

            $lookup = CarrierLookup::phone(number($record[7]));

            if ($lookup->mobile()) {
                $record[13] = $lookup->carrier;
                $writer->insertOne($record);
            }
        }

        $name = "{$this->name}_output.csv";
        $path = "fam_leads/{$name}";

        logger('FAM lead processed');
        Storage::put($path, $writer->getContent());
        logger('FAM lead written');

        cache()->put("last_fam_path", $path);

        FamFile::create([
            'name' => $this->name,
            'path' => $path,
        ]);
    }

    public function tags()
    {
        return ['ProcessFamLeads'];
    }
}
