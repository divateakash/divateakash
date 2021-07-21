<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    protected $timeframe;

    public $timeframeLabel = 'today';

    public $sentOutbounds;

    public $sentReplies;

    public $cost;

    public $revenue;

    public $goodReplies;

    public $badReplies;

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function setTimeframe($timeframe)
    {
        $this->timeframeLabel = $timeframe;

        $timeframe = [
            'today' => 'today',
            'week' => today()->startOfWeek(),
            '24' => '24 hours ago',
            '168' => '168 hours ago',
            'month' => today()->startOfMonth(),
        ][$timeframe] ?? today();

        $this->timeframe = Carbon::parse($timeframe);
        $this->loadAll();
    }

    public function getFormattedTimeframeProperty()
    {
        return $this->getTimeframe()->format('F d, Y H:i');
    }

    public function getTimeframe()
    {
        return $this->timeframe ?? today();
    }

    public function loadAll()
    {
        $this->loadSentOutbounds();
        $this->loadSentReplies();
        $this->loadCost();
        $this->loadRevenue();
        $this->loadBadReplies();
        $this->loadGoodReplies();
    }

    public function loadSentOutbounds()
    {
        $this->sentOutbounds = team()->outbounds()
            ->succeeded()
            ->where('sent_at', '>', $this->getTimeframe())
            ->count();
    }

    public function loadSentReplies()
    {
        $this->sentReplies = team()->outbounds()
            ->succeeded()
            ->where('sent_at', '>', $this->getTimeframe())
            ->reply()
            ->count();
    }

    public function loadCost()
    {
        $this->cost = team()->outbounds()
            ->succeeded()
            ->where('sent_at', '>', $this->getTimeframe())
            ->sum('cost');
    }

    public function loadRevenue()
    {
        $this->revenue = team()->conversions()
            ->where('conversions.created_at', '>', $this->getTimeframe())
            ->sum('revenue');
    }

    public function loadBadReplies()
    {
        $this->badReplies = team()->replies()->bad()->where('created_at', '>', $this->getTimeframe())->count();
    }

    public function loadGoodReplies()
    {
        $this->goodReplies = team()->replies()->good()->where('created_at', '>', $this->getTimeframe())->count();
    }

    public function getFormattedTimeframeLabelProperty()
    {
        return [
            'today' => 'Today',
            'week' => 'Current Week',
            '24' => '24 Hours',
            '168' => '7 Days',
            'month' => 'Current Month',
        ][$this->timeframeLabel];
    }
}
