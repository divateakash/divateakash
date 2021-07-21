<?php

namespace App\Http\Livewire;

use App\Campaign;
use App\Services\CarrierBreakdownService;
use Livewire\Component;

class OutboundsCarrierBreakdown extends Component
{
    public $campaign;

    public $readyToLoad = false;

    public $carriers = [];

    public function mount(Campaign $campaign)
    {
        $this->fill([
            'campaign' => $campaign,
        ]);
    }

    public function render()
    {
        return view('livewire.outbounds-carrier-breakdown');
    }

    public function load()
    {
        $this->readyToLoad = true;

        $this->carriers = resolve(CarrierBreakdownService::class)->fromCampaign($this->campaign)->all();
    }

    public function getTotalCountProperty()
    {
        return collect($this->carriers)->sum('count');
    }
}
