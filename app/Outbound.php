<?php

namespace App;

use App\DeliveryReport;
use App\Domain;
use App\DomainGroup;
use App\Exceptions\NoDomainsAvailableException;
use App\Number;
use App\Reply;
use App\Traits\CachedRelationships;
use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    use CachedRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from',
        'to',
        'cost',
        'hash',
        'link',
        'processed',
        'success',
        'error',
        'send_at',
        'sent_at',
        'response',
        'content',
        'campaign_id',
        'offer_id',
        'lead_id',
        'account_id',
        'message_group_id',
        'message_id',
        'team_id',
        'number_id',
        'reply_id',
        'domain_group_id',
        'domain_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'cost' => 'float',
        'processed' => 'boolean',
        'success' => 'boolean',
        'campaign_id' => 'integer',
        'offer_id' => 'integer',
        'lead_id' => 'integer',
        'account_id' => 'integer',
        'message_group_id' => 'integer',
        'message_id' => 'integer',
        'team_id' => 'integer',
        'number_id' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'send_at',
        'sent_at',
    ];

    public function campaign()
    {
        return $this->belongsTo(\App\Campaign::class);
    }

    public function offer()
    {
        return $this->belongsTo(\App\Offer::class);
    }

    public function lead()
    {
        return $this->belongsTo(\App\Lead::class);
    }

    public function account()
    {
        return $this->belongsTo(\App\Account::class);
    }

    public function messageGroup()
    {
        return $this->belongsTo(\App\MessageGroup::class);
    }

    public function message()
    {
        return $this->belongsTo(\App\Message::class);
    }

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function number()
    {
        return $this->belongsTo(Number::class);
    }

    public function reply()
    {
        return $this->belongsTo(Reply::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function deliveryReport()
    {
        return $this->hasOne(DeliveryReport::class)->latest('id');
    }

    public function domainGroup()
    {
        return $this->belongsTo(DomainGroup::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function randomDomain()
    {
        return Domain::where('domain_group_id', $this->domain_group_id)
            ->active()
            ->inRandomOrder()
            ->first();
    }

    public function provider()
    {
        return $this->account->provider();
    }

    public function scopeProvider($query, $provider)
    {
        return $this->whereHas('account', fn($query) =>
            $query->whereHas('provider', fn($subQuery) => $subQuery->where('provider', $provider))
        );
    }

    /**
     * Filter outbounds that have an active campaign, regular or drip.
     *
     * @param  Illuminate\Database\Eloquent\Builder  $query
     * @param  boolean $drip
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasSendingCampaign($query, $drip = false)
    {
        return $query->whereHas(
            'campaign',
            fn($query) => $query->sending()->when(
                $drip,
                fn($subQuery) => $subQuery->drip(),
                fn($subQuery) => $subQuery->regular()
            )
        );
    }

    public function scopePastSendTime($query)
    {
        return $query->where('send_at', '<=', now());
    }

    public function scopePending($query)
    {
        return $query->where('processed', false);
    }

    public function scopeSent($query)
    {
        return $query->where('processed', true);
    }

    public function scopeSucceeded($query)
    {
        return $query->where('success', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    public function scopeReply($query)
    {
        return $query->whereNotNull('reply_id');
    }

    public function scopeNotReply($query)
    {
        return $query->whereNull('reply_id');
    }

    public function scopeDelivered($query)
    {
        return $query->whereHas('deliveryReport', fn($subQuery) => $subQuery->where('status', DeliveryReport::DELIVERED));
    }

    public function delivered()
    {
        return optional($this->deliveryReport)->delivered() === true;
    }

    public function getMessageGroup()
    {
        if (optional($this->account)->messageGroup) {
            return $this->account->messageGroup;
        }

        return $this->messageGroup;
    }

    /**
     * Get a random message from the account's message group when there is one or
     * get a random message from the outbound's message group when there isn't.
     *
     * @return \App\Message
     */
    public function getRandomMessage()
    {
        if ($this->account->messageGroup) {
            return $this->account->messageGroup->randomMessage();
        }

        return $this->messageGroup->messages()->inRandomOrder()->first();
    }

    /**
     * Gets the outbound final link.
     *
     * @return string
     * @throws App\Exceptions\NoDomainsAvailableException
     */
    public function getLink($domain = null)
    {
        // if it uses an amazon link or no domain...
        if (!$this->domain_group_id) {
            return $this->link;
        }

        // the outbound processor already passes down a domain, so we shouldn't fetch it again
        $domain = $domain ?? $this->randomDomain();
        // if there are no domains available and the campaign uses it, we need to throw an exception so the campaign is paused.
        throw_if(!$domain, new NoDomainsAvailableException);

        return $this->hash ?
        "{$domain->domain}/{$this->hash}" :
        "{$domain->domain}";
    }

    public function rescheduleForStartHour()
    {
        $this->update(['send_at' => $this->lead->nextStartHour()]);
    }

    public function isReply()
    {
        return !is_null($this->reply_id);
    }

    public function resetSending()
    {
        $this->update([
            'processed' => false,
            'sent_at' => null,
        ]);
    }
}
