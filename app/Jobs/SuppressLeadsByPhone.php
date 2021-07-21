<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SuppressLeadsByPhone implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;

    public $team;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone, $team)
    {
        $this->phone = number($phone);
        $this->team = $team;
        $this->onQueue('suppress-leads');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $suppression = $this->team->suppressions()->firstOrCreate([
            'phone' => $this->phone,
        ]);

        $suppression->suppressLeads();
    }

    public function tags()
    {
        return ['SupressLeadsWithPhone'];
    }
}
