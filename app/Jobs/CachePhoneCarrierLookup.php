<?php

namespace App\Jobs;

use App\Support\CarrierLookup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CachePhoneCarrierLookup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
        $this->onQueue('convert-file-upload-record');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        CarrierLookup::phone(number($this->phone));
    }

    public function tags()
    {
        return ['CachePhoneCarrierLookup'];
    }
}
