<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $group;
    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($group, $message)
    {
        $this->group = $group;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->group->messages()->create([
            'content' => $this->message,
        ]);
    }

    public function tags()
    {
        return ['ImportMessage'];
    }
}
