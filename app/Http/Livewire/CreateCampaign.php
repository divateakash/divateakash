<?php

namespace App\Http\Livewire;

use App\Campaign;
use App\Carrier;
use Livewire\Component;

class CreateCampaign extends Component
{
    public $drip;

    public $accounts;

    public $domainGroups;

    public $linkType = 'hash';

    public $domainGroupId;

    public $lists;

    public $selectedList;

    public $selectedRepliersList;

    public $offers;

    public $messageGroups;

    public $replyMessageGroups;

    public $toSend = 0;

    public $skip = 0;

    public $limit = null;

    public $carriers = null;

    public $data;

    public function mount($drip = false)
    {
        $this->fill([
            'drip' => $drip,
            'accounts' => team()->accounts()->validForSending()->get(),
            'lists' => team()->catalogs,
            'offers' => team()->offers,
            'messageGroups' => team()->messageGroups()->type('first')->has('messages')->get(),
            'replyMessageGroups' => team()->messageGroups()->type('reply')->has('messages')->get(),
            'domainGroups' => team()->domainGroups()->has('activeDomains')->get(),
            'data' => [],
        ]);

        $this->fillCarriers();
        $this->fillFromSource();

    }

    public function render()
    {
        return view('livewire.create-campaign');
    }

    public function recalculateAmountToSend()
    {
        $toSend = collect($this->selectedList ?? [])->reduce(function ($carry, $list) {
            $list = team()->catalogs()->find($list);

            if (!$list) {
                return 0;
            }

            $toSend = $list->amountToSend(Carrier::findMany($this->carriers));

            if ($this->skip && is_numeric($this->skip) && !$this->getShouldNotShowFiltersProperty()) {
                $toSend -= $this->skip;
            }

            if ($this->limit && is_numeric($this->limit) && !$this->getShouldNotShowFiltersProperty()) {
                if ($this->skip && $this->limit > $toSend) {
                    $this->limit = $toSend;
                }

                $toSend -= ($toSend - $this->limit);
            }

            if ($toSend < 0) {
                $toSend = 0;
                $this->limit = 0;
                $this->skip = 0;
            }

            return $toSend + $carry;
        }, 0);

        $this->toSend = $toSend;
    }

    public function getShouldNotShowFiltersProperty()
    {
        return is_array($this->selectedList) && count($this->selectedList) > 1;
    }

    public function getCatalogFieldNameProperty()
    {
        return $this->drip ? 'catalog_id' : 'catalog_id[]';
    }

    public function fillFromSource()
    {
		
        $campaign = Campaign::whereUuid(request('source'))->first() ?? new Campaign;

        $this->data['drip_wait_hours'] = $campaign->drip_wait_hours;
        $this->data['drip_skip_weekends'] = $campaign->drip_skip_weekends ? 'yes' : null;
        $this->data['drip_time_limit'] = $campaign->drip_time_limit;
        $this->data['name'] = $campaign->name;
        $this->data['message_type'] = $campaign->message_type;
        $this->linkType = $campaign->link_type ?? 'hash';
        $this->domainGroupId = $campaign->domain_group_id;
        $this->selectedList = $campaign->catalog_id;
        $this->data['offer_id'] = $campaign->offer_id;
        $this->data['account_id'] = $campaign->account_id;
        $this->data['message_group_id'] = $campaign->message_group_id;
        $this->data['reply_account_id'] = $campaign->reply_account_id;
        $this->data['reply_message_group_id'] = $campaign->reply_message_group_id;
        $this->selectedRepliersList = $campaign->repliers_catalog_id;
        $this->carriers = $campaign->carriers ? $campaign->carriers : $this->carriers;

        $this->recalculateAmountToSend();
    }

    public function fillCarriers()
    {
        $this->carriers = Carrier::all()->map->id->all();
    }
}
