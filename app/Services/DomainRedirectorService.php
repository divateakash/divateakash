<?php

namespace App\Services;

use App\Click;
use App\Domain;
use App\Outbound;
use Illuminate\Support\Facades\Cache;

class DomainRedirectorService
{
    public function __invoke($code = null)
    {
        $domain = $this->getDomain();

        $outbound = Outbound::where('hash', $code)->latest('id')->firstOrFail();

        $offer = $outbound->cachedRelation('offer');
        $campaign = $outbound->cachedRelation('campaign');

        $this->addClick($outbound);

        return view('amazon.redirect', [
            'redirect' => $this->buildRedirectUrl($outbound),
        ]);
    }

    public function getDomain()
    {
        $domain = request()->server('HTTP_HOST');

        return Cache::remember("domains.{$domain}", now()->addMinutes(30), fn() =>
            Domain::where('domain', $domain)->firstOrFail()
        );
    }

    public function addClick($outbound)
    {
        return Click::firstOrCreate([
            'offer_id' => $outbound->offer_id,
            'domain_id' => $outbound->domain_id,
            'message_id' => $outbound->message_id,
            'number_id' => $outbound->number_id,
            'campaign_id' => $outbound->campaign_id,
            'outbound_id' => $outbound->id,
            'team_id' => $outbound->team_id,
        ], [
            'bot' => false,
        ]);
    }

    public function buildRedirectUrl($outbound)
    {
        $lead = $outbound->cachedRelation('lead');

        $query = http_build_query(array_filter([
            'campaignkey' => $outbound->campaign->uuid,
            'first_name' => $outbound->lead->first_name,
            'last_name' => $outbound->lead->last_name,
            'email' => $outbound->lead->email,
            'phone' => $outbound->lead->phone,
        ]));

        return $outbound->offer->redirect_url . "?{$query}";
    }
}
