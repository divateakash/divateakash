<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use MadnessCODE\Voluum;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
 */

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('voluum', function () {
    $access_key_id = "c67c7565-1b72-447c-9715-3d07099bc61c";
    $access_key = "aZ-cBDiyHor4q8nREvRHXf20e9Wj1maNETXE";

    $client = new Voluum\Client\API(new Voluum\Auth\AccessKeyCredentials($access_key_id, $access_key));

    $report_api = new Voluum\API($client);

    $start = Carbon::today();
    $end = Carbon::tomorrow();

    $result = $report_api->get("report/conversions", [
        "from" => $start->toIso8601String(),
        "to" => $end->toIso8601String(),
        "columns" => "campaign",
        "tz" => "EST",
        "limit" => 100,
    ]);

    $results = json_decode($result->getJson(), true);
    dd($results);
    dd(collect($results['rows'])->take(100));
});
