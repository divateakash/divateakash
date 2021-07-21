<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Campaign extends Component
{
    public $campaign;

    public $readyToLoad = false;

    public function mount($campaign)
    {
        $this->campaign = $campaign;
    }

    public function render()
    {
        return view('livewire.campaign');
    }
	
	public function toggleStatus()
    {
        if ($this->campaign->status === 'finished') {
            return;
        }

        $newStatus = $this->campaign->status === 'sending' ? 'paused' : 'sending';

        $this->campaign->status = $newStatus;
        $this->campaign->save();

        $this->loadCounts();
    }

    public function finishCampaign()
    {
        $this->campaign->status = 'finished';
        $this->campaign->save();

        $this->loadCounts();
    }

    public function loadCounts()
    {
        $this->readyToLoad = true;

        $this->campaign->load('conversions');
        $this->campaign->loadCount([
            'outbounds', 'sentOutbounds', 'failedOutbounds', 'pendingRepliesOutbounds', 'sentReplyOutbounds', 'deliveredReplyOutbounds', 'replies', 'goodReplies', 'clicks',
        ]);
    }
}
