<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IncrementFileUploadBreakdown implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fileUpload;

    public $attribute;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileUpload, $attribute)
    {
        $this->onQueue('increment-file-upload-breakdown');
        $this->fileUpload = $fileUpload;
        $this->attribute = $attribute;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->fileUpload->{$this->attribute}++;
        $this->fileUpload->save();
    }
}
