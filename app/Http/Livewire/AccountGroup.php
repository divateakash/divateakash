<?php

namespace App\Http\Livewire;

use App\Account;
use Livewire\Component;

class AccountGroup extends Component
{
    public $account;

    public $newAccount;

    public $availableAccounts;

    public $attachedAccounts;

    public function mount(Account $account)
    {
        $this->fill([
            'account' => $account,
            'attachedAccounts' => $this->getAttachedAccounts($account),
            'availableAccounts' => $this->getAvailableAccounts($account),
        ]);
    }

    public function render()
    {
        return view('livewire.account-group');
    }

    public function getAvailableAccounts($account)
    {
        $except = array_merge($account->accounts()->get()->map->id->all(), [$account->id]);

        return $account->team->accounts()->singular()->whereNotIn('id', $except)->with('provider')->withCount('numbers')->get();
    }

    public function getAttachedAccounts($account)
    {
        return $account->accounts()->with('provider')->withCount('numbers')->get();
    }

    public function attachNewAccount()
    {
        $this->account->accounts()->attach($this->account->team->accounts()->find($this->newAccount));
    }

    public function detachAccount()
    {
        dd(1);
        $this->account->accounts()->detach($id);
    }
}
