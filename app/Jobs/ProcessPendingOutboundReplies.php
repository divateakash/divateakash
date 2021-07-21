<?php

namespace App\Jobs;

use App\Account;
use App\Campaign;
use App\Jobs\ProcessPendingOutbound;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPendingOutboundReplies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;

    public function __construct($account = null)
    {
        $this->account = $account;
        $this->onQueue('process-pending-outbound-replies');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->account) {
            return $this->dispatchJobs();
        }

        $outbounds = $this->account
            ->outbounds()
            ->pending()
            ->reply()
            ->where('created_at', '>', now()->subMinutes(20))
            ->inRandomOrder()
            ->take(20)
            ->get();

        foreach ($outbounds->unique('message_group_id') as $outbound) {
            ProcessPendingOutbound::dispatch($outbound, $outbound->account);
        }

        // now let's wait 5 seconds before sending the rest.

        sleep(10);

        // Here we calculate the amount of sends per second and divide the
        // outbounds in batches, adding a delay for each batch. That way
        // we do not overload our message provider and ensure DLRs
        // are delivered within the needed timeframe.
        $outboundsCount = $outbounds->count() > 0 ? $outbounds->count() : 1;
        $amountPerSecond = ceil($outboundsCount / 40);

        foreach ($outbounds->chunk($amountPerSecond) as $index => $chunkedOutbounds) {
            $delay = $index + 1;
            foreach ($chunkedOutbounds as $outbound) {
                ProcessPendingOutbound::dispatch($outbound, $outbound->account)
                    ->delay(now()->addSeconds($delay));
            }
        }
    }

    public function dispatchJobs()
    {
        $accounts = Campaign::query()
            ->whereHas('outbounds', fn($query) => $query->pending()->reply())
            ->whereHas('replyMessageGroup', fn($query) => $query->has('messages'))
            ->where('created_at', '>', today()->subDay())
            ->get()
            ->map
            ->replyAccount
            ->unique();

        foreach ($accounts as $account) {
            dispatch(new self($account));
        }
    }

    public function tags()
    {
        if ($this->account) {
            return [
                'ProcessPendingOutboundReplies', 'account:' . $this->account->id,
            ];
        }

        return ['ProcessPendingOutboundReplies'];
    }
}
