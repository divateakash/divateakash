<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $timeframe = [
            'today' => 'today',
            'week' => today()->startOfWeek(),
            '24' => '24 hours ago',
            '168' => '168 hours ago',
            'month' => today()->startOfMonth(),
        ][request('timeframe')] ?? today();

        $timeframe = Carbon::parse($timeframe);

        return view('dashboard.index');
    }
}
