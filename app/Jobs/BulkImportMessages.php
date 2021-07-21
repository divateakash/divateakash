<?php

namespace App\Jobs;

use App\Jobs\ImportMessage;
use App\Support\Spintax;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BulkImportMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $group;

    public $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($group, $file)
    {
        $this->group = $group;
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $string = str_replace("\r", "\n", $this->file);
        $messages = explode("\n", $string);

        $messages = collect($messages)
            ->filter(fn($message) => Spintax::longest($message) <= config('konnectext.max_chars'))
            ->map(fn($message) => str_replace('"', '', $message))
            ->filter()
            ->each(fn($message) =>
                ImportMessage::dispatch($this->group, str_replace('"', '', $message))
            );
    }

    public function tags()
    {
        return ['BulkImportMessages'];
    }
}
