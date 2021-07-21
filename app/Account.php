<?php

namespace App;

use App\Account;
use App\ActivityLog;
use App\MessageGroup;
use App\Number;
use App\Outbound;
use App\Reply;
use App\Traits\IsProcessed;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use IsProcessed;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'send_rate',
        'provider_id',
        'is_group',
        'team_id',
        'message_group_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'provider_id' => 'integer',
        'team_id' => 'integer',
        'is_group' => 'boolean',
    ];

    public function provider()
    {
        return $this->belongsTo(\App\Provider::class);
    }

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function numbers()
    {
        return $this->hasMany(Number::class);
    }

    public function randomNumber()
    {
        return $this->numbers()->inRandomOrder()->first();
    }

    public function outbounds()
    {
        return $this->hasMany(Outbound::class);
    }

    public function messageGroup()
    {
        return $this->belongsTo(MessageGroup::class);
    }

    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'group_accounts', 'parent_id', 'account_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function badReplies()
    {
        return $this->replies()->bad();
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'model');
    }

    public function scopeGroup($query)
    {
        return $query->where('is_group', true);
    }

    public function scopeSingular($query)
    {
        return $query->where('is_group', false);
    }

    public function scopeValidForSending($query)
    {
        return $query->where(fn($query) =>
            $query->has('numbers')->orWhere('is_group', true)
        );
    }

    public function sendPerHour()
    {
        $numbersCount = $this->numbers_count ?? $this->numbers()->count();

        return $numbersCount * $this->send_rate;
    }

    /**
     * Attaches an account to the group.
     *
     * @param  \App\Account $account
     * @return void
     */
    public function attachAccount(Account $account, $reason = null)
    {
        $this->accounts()->attach($account);

        $content = "Account {$account->name} / {$account->id} attached";

        if ($reason) {
            $content .= "due to {$reason}";
        }

        $this->activityLogs()->create([
            'content' => $content,
        ]);
    }

    public function detachAccount(Account $account, $reason = null)
    {
        $this->accounts()->detach($account);

        $content = "Account {$account->name} / {$account->id} detached";

        if ($reason) {
            $content .= " due to {$reason}";
        }

        $this->activityLogs()->create([
            'content' => $content,
        ]);
    }

    /**
     * If the account is not a group, it means the only account is itself.
     *
     * @return \App\Account|$this
     */
    public function getRandomAccount()
    {
        if (!$this->is_group) {
            return $this;
        }

        return $this->accounts()->inRandomOrder()->first();
    }

    public function getNumbers()
    {
        if ($this->is_group) {
            return Number::whereIn('account_id', $this->accounts->map->id)->get();
        }

        return $this->numbers()->get();
    }

    public function getNumbersCount()
    {
        if ($this->is_group) {
            return Number::whereIn('account_id', $this->accounts->map->id)->count();
        }

        return $this->numbers()->count();
    }

    public function getSendingPriceAttribute()
    {
        return $this->provider->cost;
    }

    public function getProviderNameAttribute()
    {
        return $this->provider->provider;
    }

    public function getDefaultSendRate()
    {
        return [
            'gorilla' => [
                'longcode' => 15,
            ],
            'txtria' => [
                'longcode' => 4,
            ],
        ][$this->provider->provider][$this->provider->type] ?? 15;
    }

    public function averageOptOut($timeframe = 24)
    {
        $after = now()->subHours($timeframe);

        $totalReplies = ($this->replies_count ?? $this->replies()->after($after)->count());
        $badReplies = $this->bad_replies_count ?? $this->badReplies()->after($after)->count();

        return (float) $badReplies / ($totalReplies ?: 1);
    }
}
