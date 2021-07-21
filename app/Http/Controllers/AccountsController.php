<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests\AccountsControllerStoreRequest;
use App\Http\Requests\AccountStoreRequest;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $accounts = team()->accounts()->with('messageGroup')->withCount('numbers', 'accounts')->get();

        return view('accounts.index', compact('accounts'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Account $account
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Account $account)
    {
        $numbers = $account->numbers()->withCount('outbounds')->take(20)->paginate();
        $account->loadCount('numbers');

        $attachedAccounts = $account->accounts()->with('provider')->withCount('numbers')->get();

        $except = array_merge($account->accounts()->get()->map->id->all(), [$account->id]);
        $availableAccounts = $account->team->accounts()->singular()->whereNotIn('id', $except)->with('provider')->withCount('numbers')->get();

        $logs = $account->activityLogs()->latest()->get();

        if ($account->is_group) {
            $attachedAccounts->loadCount([
                'replies' => fn($query) => $query->after(now()->subHours(24)),
                'badReplies' => fn($query) => $query->after(now()->subHours(24)),
            ]);
        }

        return view('accounts.show', compact('account', 'numbers', 'attachedAccounts', 'availableAccounts', 'logs'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    /**
     * @param \App\Http\Requests\AccountsControllerStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountStoreRequest $request)
    {
        $account = team()->accounts()->make($request->all());
        $account->send_rate = $account->getDefaultSendRate();
        $account->save();

        return redirect()->route('accounts.index');
    }

    public function attachSubaccount(Account $account)
    {
        foreach (request('accounts') as $accountId) {
            $account->attachAccount(Account::find($accountId));
        }

        return back();
    }

    public function detachSubaccount(Account $account, Account $subAccount)
    {
        $account->detachAccount($subAccount);

        return back();
    }
}
