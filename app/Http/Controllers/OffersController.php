<?php

namespace App\Http\Controllers;

use App\Http\Requests\OffersControllerStoreRequest;
use App\Http\Requests\OfferStoreRequest;
use App\Offer;
use Illuminate\Http\Request;

class OffersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $offers = team()->offers;

        return view('offers.index', compact('offers'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Offer $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Offer $offer)
    {
        return view('offers.show', compact('offer'));
    }

    public function create()
    {
        return view('offers.create');
    }

    /**
     * @param \App\Http\Requests\OffersControllerStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfferStoreRequest $request)
    {
        $offer = team()->offers()->create($request->all());

        return redirect()->route('offers.index');
    }
}
