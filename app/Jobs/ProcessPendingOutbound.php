<?php

namespace App\Jobs;

use App\Exceptions\LeadReceivedMessageRecentlyException;
use App\Exceptions\NoDomainsAvailableException;
use App\Exceptions\Sending\NoMessageAvailableException;
use App\Exceptions\Sending\OutboundOutsideSendingHoursException;
use App\Messaging\Providers\Provider;
use App\Services\OutboundProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPendingOutbound implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $outbound;

    public $account;

    public $provider;

    public $number;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($outbound, $account, $provider = null, $number = null)
    {
        $this->onQueue($outbound->isReply() ? 'process-pending-outbound-replies' : 'process-pending-outbound');
        $this->outbound = $outbound;
        $this->account = $account;
        $this->provider = $provider ?? optional($account)->provider_name;
        $this->number = $number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (is_null($this->provider)) {
            return;
        }

        $provider = Provider::factory($this->provider);

        try {
            return resolve(OutboundProcessor::class)->process($this->outbound, $provider, $this->account, $this->number);
        } catch (OutboundOutsideSendingHoursException $exception) {
            return $this->outbound->rescheduleForStartHour();
        } catch (NoMessageAvailableException $exception) {
            if (!$this->outbound->isReply()) {
                return $this->outbound->campaign->pause();
            }
        } catch (NoDomainsAvailableException $exception) {
            $this->outbound->campaign->pause();
        } catch (LeadReceivedMessageRecentlyException $exception) {
            return $this->outbound->update([
                'error' => 'Lead received a message within the last 24 hours.',
                'response' => 'Lead received a message within the last 24 hours.',
                'processed' => true,
                'sent_at' => now(),
                'success' => false,
            ]);
        }

    }

    public function tags()
    {
        $tags = [
            'ProcessPendingOutbound',
            'outbound:' . $this->outbound->id,
        ];

        if ($this->outbound->isReply()) {
            $tags[] = 'outbound:reply';
        }

        return $tags;
    }
}
