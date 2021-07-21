<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProvidersControllerStoreRequest;
use App\Http\Requests\ProvidersControllerUpdateRequest;
use App\Http\Requests\ProviderStoreRequest;
use App\Http\Requests\ProviderUpdateRequest;
use App\Provider;
use Illuminate\Http\Request;

class ProvidersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $providers = team()->providers()->withCount('numbers')->get();

        return view('providers.index', compact('providers'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Provider $provider
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Provider $provider)
    {
        $accounts = $provider->accounts->loadCount('numbers');

        return view('providers.show', compact('provider', 'accounts'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('providers.create');
    }

    /**
     * @param \App\Http\Requests\ProvidersControllerStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProviderStoreRequest $request)
    {
        $provider = team()->providers()->create($request->all());

        $request->session()->flash('provider.name', $provider->name);

        return redirect()->route('providers.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Provider $provider
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Provider $provider)
    {
        return view('providers.edit', compact('provider'));
    }

    /**
     * @param \App\Http\Requests\ProvidersControllerUpdateRequest $request
     * @param \App\Provider $provider
     * @return \Illuminate\Http\Response
     */
    public function update(ProviderUpdateRequest $request, Provider $provider)
    {
        $provider->update($request->all());

        $request->session()->flash('provider.name', $provider->name);

        return redirect()->route('providers.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Provider $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Provider $provider)
    {
        return redirect()->route('providers.index');
    }
}
