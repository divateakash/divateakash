<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Catalog;
use App\Http\Requests\CampaignStoreRequest;
use App\Rules\BelongsToTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DripsController extends Controller
{
    public function index()
    {
        $campaigns = team()
            ->campaigns()
            ->drip()
            ->latest()
            ->when(request('filter'), fn($query, $filter) =>
                $query->where(fn($query) =>
                    $query->where('name', 'LIKE', "%{$filter}%")->orWhereHas('catalog', fn($subQuery) => $subQuery->where('name', 'LIKE', "%{$filter}%"))
                )
            )
            ->with(['account', 'replyAccount', 'messageGroup', 'replyMessageGroup', 'catalog', 'repliersCatalog'])
            ->paginate(10);

        return view('drips.index', compact('campaigns'));
    }

    public function create()
    {
        return view('drips.create');
    }

    /**
     * @param \App\Http\Requests\CampaignsControllerStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampaignStoreRequest $request)
    {
        if (!request()->has('drip_wait_hours')) {
            throw ValidationException::withMessages([
                'drip_wait_hours' => 'Please specify the wait time.',
            ]);
        }

        request()->validate([
            'catalog_id' => ['required', 'integer', new BelongsToTeam(Catalog::class, team())],
        ]);

        $campaign = $this->createCampaign($request->all());

        return redirect()->route('drips.index');
    }

    protected function createCampaign($data)
    {
        $data['drip_skip_weekends'] = request()->boolean('drip_skip_weekends', false);
        $data['type'] = Campaign::Drip;
        $data['status'] = 'paused';

        return DB::transaction(function () use ($data) {
            $campaign = team()->campaigns()->create($data);
            if ($campaign->usesAmazonLinks()) {
                try {
                    $campaign->generateAmazonLinks();
                } catch (\Exception $e) {
                    throw ValidationException::withMessages([
                        'amazon' => 'We could not generate your amazon links. Please check your AWS key, secret and bucket or contact support.',
                    ]);
                }
            }

            return $campaign;
        });
    }
}
