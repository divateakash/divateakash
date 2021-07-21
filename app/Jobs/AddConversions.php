<?php

namespace App\Jobs;

use App\Campaign;
use App\Conversion;
use App\Team;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MadnessCODE\Voluum;

class AddConversions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $team;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($team = null)
    {
        $this->team = $team;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->team) {
            return $this->dispatchJobs();
        }

        $client = new Voluum\Client\API(new Voluum\Auth\AccessKeyCredentials($this->team->voluum_api_user, $this->team->voluum_api_key));

        $reportApi = new Voluum\API($client);

        $start = Carbon::today();
        $end = Carbon::tomorrow();

        $result = $reportApi->get('report/conversions', [
            'from' => $start->toIso8601String(),
            'to' => $end->toIso8601String(),
            'columns' => 'campaign',
            'tz' => 'UTC',
            'limit' => 1000,
        ]);

        $results = collect(json_decode($result->getJson(), true)['rows']);

        foreach ($results as $result) {
            $this->addConversion($result);
        }
    }

    public function addConversion($result)
    {
        $campaign = Campaign::whereUuid($result['customVariable1'])->first();

        if (!$campaign) {
            return;
        }

        return Conversion::firstOrCreate([
            'click_id' => $result['clickId'],
        ], [
            'client_id' => $result['clientId'],
            'cost' => $result['cost'],
            'revenue' => $result['revenue'],
            'profit' => $result['profit'],
            'campaign_id' => $campaign->id,
        ]);
    }

    public function dispatchJobs()
    {
        foreach (Team::whereNotNull('voluum_api_user')->get() as $team) {
            self::dispatch($team);
        }
    }

    public function tags()
    {
        if ($this->team) {
            return ['AddConversions', 'team:' . $this->team->id];
        }

        return [
            'AddConversions',
        ];
    }
}
