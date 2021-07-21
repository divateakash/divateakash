<?php

namespace App\Console\Commands;

use App\Outbound;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use League\Csv\Writer;

class GenerateDeliveryReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delivery-reports:generate {--success}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $campaigns = $this->ask('Campaign IDs');
        $since = Carbon::parse($this->ask('Since'));

        $outbounds = Outbound::query()
            ->where('sent_at', '>', $since)
            ->when($campaigns, fn($query) => $query->whereIn('campaign_id', explode(',', $campaigns)))
            ->whereHas('deliveryReport', fn($query) =>
                $query->when($this->option('success') == false, fn($subQuery) => $subQuery->whereNotNull('error'))
            )
            ->with('deliveryReport')
            ->get();

        $mapped = $outbounds->map(fn($outbound) =>
            [
                'from' => $outbound->from,
                'to' => $outbound->to,
                'content' => $outbound->content,
                'sent_at' => $outbound->sent_at->toDateTimeString(),
                'dlr_received_at' => $outbound->deliveryReport->created_at->toDateTimeString(),
                'delay' => $outbound->deliveryReport->created_at->diffInSeconds($outbound->sent_at),
                'error' => $outbound->deliveryReport->meta['error'],
                'tracking_id' => $outbound->response,
            ]
        )->sortByDesc('delay')->values();

        $writer = Writer::createFromString();

        $writer->insertOne([
            'from', 'to', 'content', 'sent_at', 'dlr_received_at', 'delay', 'error', 'tracking_id',
        ]);

        $writer->insertAll($mapped->toArray());

        $filename = Str::random(4) . "_dlr_reports_since_{$since}";

        if ($campaigns) {
            $filename .= "campaigns_" . str_replace(',', '_', $campaigns);
        }

        $filename .= '.csv';

        file_put_contents("public/reports/{$filename}", $writer->getContent());

        $this->comment("Link: " . url('reports/' . $filename));
    }
}
