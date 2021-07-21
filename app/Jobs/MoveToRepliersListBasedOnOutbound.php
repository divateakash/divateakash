<?php

namespace App\Jobs;

use App\Catalog;
use App\Outbound;
use App\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoveToRepliersListBasedOnOutbound implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $outbond;

    public $reply;

    public $repliersCatalog;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Outbound $outbound, Reply $reply, Catalog $repliersCatalog)
    {
        $this->outbound = $outbound;
        $this->reply = $reply;
        $this->repliersCatalog = $repliersCatalog;
        $this->onQueue('move-to-repliers-list');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->repliersCatalog->leads()->where('phone', $this->outbound->to)->firstOr(fn() =>
            $this->outbound->lead->replicate(['catalog_id'])
                ->fill(['catalog_id' => $this->repliersCatalog->id])
                ->save()
        );
    }
}
