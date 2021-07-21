<?php

namespace App\Jobs;

use App\Jobs\IncrementFileUploadBreakdown;
use App\Lead;
use App\Support\CarrierLookup;
use App\Suppression;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertFileUploadRecord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fileUpload;

    public $record;

    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileUpload, $record)
    {
        $this->onQueue('convert-file-upload-record');
        $this->fileUpload = $fileUpload;
        $this->record = array_values($record);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            'first_name' => $this->getColumn('first_name'),
            'last_name' => $this->getColumn('last_name'),
            'email' => $this->getColumn('email'),
            'phone' => number($this->getColumn('phone')),
            'region' => $this->getColumn('state'),
            'city' => $this->getColumn('city'),
            'catalog_id' => $this->fileUpload->catalog_id,
            'team_id' => $this->fileUpload->team_id,
        ];

        if ($this->fileUpload->catalog->leads()->where('phone', $data['phone'])->exists()) {
            return IncrementFileUploadBreakdown::dispatch($this->fileUpload, 'duplicates');
        }

        $carrierInformation = CarrierLookup::phone($data['phone']);

        if (!$carrierInformation->mobile()) {
            return IncrementFileUploadBreakdown::dispatch($this->fileUpload, 'landlines');
        }

        if (
            $this->isSuppressed($data['phone'], $this->fileUpload->team_id)
        ) {
            return IncrementFileUploadBreakdown::dispatch($this->fileUpload, 'rejected');
        }
        [$startHour, $endHour] = $carrierInformation->sendingHours();

        $data['city'] = $data['city'] ?: $carrierInformation->city;
        $data['region'] = $data['region'] ?: $carrierInformation->region;
        $data['timezone'] = $carrierInformation->timezone;
        $data['carrier'] = $carrierInformation->carrier;
        $data['carrier_id'] = $carrierInformation->carrierObject()->id;
        $data['type'] = $carrierInformation->type;
        $data['start_hour'] = $startHour;
        $data['end_hour'] = $endHour;

        Lead::create($data);
    }

    public function getColumn($column)
    {
        if (
            !array_key_exists($column, $this->fileUpload->mapping) ||
            !array_key_exists($this->fileUpload->mapping[$column], $this->record)
        ) {
            return null;
        }

        return $this->record[$this->fileUpload->mapping[$column]];
    }

    public function isSuppressed($phone, $teamId)
    {
        return Suppression::isSuppressed($teamId, $phone) || Lead::where('team_id', $teamId)->where('phone', $phone)->whereNotNull('suppressed_at')->exists();
    }

    public function tags()
    {
        return ['ConvertFileUploadRecord'];
    }
}
