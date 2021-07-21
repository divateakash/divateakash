<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Http\Requests\CampaignsControllerStoreRequest;
use App\Http\Requests\CampaignStoreRequest;
use App\Jobs\GenerateMessages;
use App\Jobs\ProcessPendingOutbound;
use App\Services\OutboundFilteringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class CampaignsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $campaigns = team()
            ->campaigns()
            ->regular()
            ->latest()
            ->when(request('old'), fn($query) => $query->where('created_at', '<', today()), fn($query) => $query->where('created_at', '>', today()))
            ->with(['account', 'replyAccount', 'messageGroup', 'replyMessageGroup', 'catalog', 'repliersCatalog'])
            ->paginate(10);

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Campaign $campaign
     * @return \Illuminate\Http\Response
     */
	 
    public function show(Request $request, Campaign $campaign)
    {
        Gate::authorize('view', $campaign);

        return view('campaigns.index', compact('campaign'));
    }

    public function create()
    {
        return view('campaigns.create');
    }

    /**
     * @param \App\Http\Requests\CampaignsControllerStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampaignStoreRequest $request)
    {
		$campaigns = $this->createCampaigns($request->all());

        foreach ($campaigns as $campaign) {
            GenerateMessages::dispatch($campaign);
        }

        $request->session()->flash('campaign.name', $campaign->name);

        return redirect()->route('campaigns.index');
    }

    public function outbounds(Campaign $campaign)
    {
        Gate::authorize('view', $campaign);

        $outbounds = resolve(OutboundFilteringService::class)->filter($campaign->outbounds()->latest('sent_at'))
            ->with('deliveryReport', 'account', 'lead')
            ->paginate(100);

        return view('campaigns.outbounds.index', compact('campaign', 'outbounds'));
    }

    public function replies(Campaign $campaign)
    {
        Gate::authorize('view', $campaign);

        $replies = $campaign->replies()->latest('id')->paginate(100);

        return view('campaigns.replies.index', compact('campaign', 'replies'));
    }

    public function pendingReplies(Campaign $campaign)
    {
        Gate::authorize('view', $campaign);

        $outbounds = $campaign->outbounds()->pending()->reply()->with(['messageGroup.messages', 'account.messageGroup.messages'])->paginate(100);

        return view('campaigns.pending-replies.index', compact('campaign', 'outbounds'));
    }

    public function retryPendingReplies(Campaign $campaign)
    {
        Gate::authorize('view', $campaign);

        $outbounds = $campaign->outbounds()->pending()->reply()->with('account')->get();

        foreach ($outbounds as $key => $outbound) {
            ProcessPendingOutbound::dispatch($outbound, $outbound->account)
                ->delay(now()->addSeconds($key + 1));
        }

        return back();
    }

    public function scramblePendingReplies(Campaign $campaign)
    {
        Gate::authorize('view', $campaign);

        $outbounds = $campaign->outbounds()->pending()->reply()->get();

        if (!$campaign->getReplyAccount()) {
            throw ValidationException::withMessages([
                'reply_account' => 'There is no reply account available.',
            ]);
        }

        foreach ($outbounds as $outbound) {
            $outbound->update(['account_id' => $campaign->getReplyAccount()->id]);
        }

        return back();
    }

    protected function createCampaigns($data)
    {
        return DB::transaction(function () use ($data) {
            $catalogsCount = count($data['catalog_id']);

            return collect($data['catalog_id'])->map(function ($catalogId, $key) use ($data, $catalogsCount) {
                $creationData = $data;
                $creationData['catalog_id'] = $catalogId;

                if ($catalogsCount > 1) {
                    $creationData['name'] .= ' ' . ($key + 1) . "/{$catalogsCount}";
                }

                $campaign = team()->campaigns()->create($creationData);
                if ($campaign->usesAmazonLinks()) {
                    try {
                        $campaign->generateAmazonLinks();
                    } catch (\Exception $e) {
                        logger($e->getMessage());
                        throw ValidationException::withMessages([
                            'amazon' => 'We could not generate your amazon links. Please check your AWS key, secret and bucket or contact support.',
                        ]);
                    }
                }
				
				//echo"<pre/>";print_r($campaign);die;

                return $campaign;
            });
        });

    }
}
