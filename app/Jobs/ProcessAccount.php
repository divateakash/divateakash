<?php

namespace App\Jobs;

use App\Account;
use App\Jobs\ProcessPendingOutbound;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;

    public $drip = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account = null, $drip = false)
    {
        $this->onQueue($this->getQueue($account));
        $this->account = $account;
        $this->drip = $drip;
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

        $amountToPick = (int) $this->account->sendPerHour() / 60;

        $amountToPick = $amountToPick >= 1 ? $amountToPick : 1;

        $outbounds = $this->account
            ->outbounds()
            ->pending()
            ->pastSendTime()
            ->notReply()
            ->hasSendingCampaign($this->drip)
            ->take($amountToPick)
            ->inRandomOrder()
            ->with('account')
            ->get();

        $provider = $this->account->provider;

        $numbers = $this->account->getNumbers();

        // first, let's send a message of each type so we can wait for DLRs.

        foreach ($outbounds->unique('message_group_id') as $outbound) {
            ProcessPendingOutbound::dispatch($outbound, $this->account, $provider->provider, $numbers->random());
        }

        // now let's wait 15 seconds before sending the rest.

        sleep(10);

        // Here we calculate the amount of sends per second and divide the
        // outbounds in batches, adding a delay for each batch. That way
        // we do not overload our message provider and ensure DLRs
        // are delivered within the needed timeframe.
        $outboundsCount = $outbounds->count() > 0 ? $outbounds->count() : 1;
        $amountPerSecond = ceil($outboundsCount / 40);

        foreach ($outbounds->chunk($amountPerSecond) as $index => $chunkedOutbounds) {
            foreach ($chunkedOutbounds as $outbound) {
                ProcessPendingOutbound::dispatch($outbound, $outbound->account, $provider->provider, $numbers->random())
                    ->delay(now()->addSeconds($index));
            }
        }

        if (!$this->drip) {
            $this->account->finishProcessing();
        }
    }

    public function dispatchJobs()
    {
        $accounts = Account::whereHas('outbounds', fn($query) =>
            $query->pending()->notReply()->whereHas('campaign', fn($subQuery) =>
                $subQuery->sending()->regular()
            )
        )->get()->reject(fn($account) => $account->beingProcessed());

        foreach ($accounts as $account) {
            $account->startProcessing();
            dispatch(new self($account));
        }

        $dripAccounts = Account::whereHas('outbounds', fn($query) =>
            $query->pending()->notReply()->whereHas('campaign', fn($subQuery) =>
                $subQuery->sending()->drip()
            )
        )->get();

        foreach ($dripAccounts as $account) {
            dispatch(new self($account, true));
        }
    }

    public function getQueue($account)
    {
        if ($account) {
            return 'process-account';
        }

        return 'dispatch-account-jobs';
    }

    public function tags()
    {
        if ($this->account) {
            return [
                'ProcessAccount', 'account:' . $this->account->id, 'drip:' . $this->drip ? 'true' : 'false',
            ];
        }

        return [
            'ProcessAccounts',
        ];
    }
}
