<?php

namespace App;

use App\Carrier;
use App\Click;
use App\Conversion;
use App\DeliveryReport;
use App\DomainGroup;
use App\Outbound;
use App\Reply;
use App\Services\AmazonLinkService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Campaign extends Model
{
    const Campaign = 'Campaign';
    const Drip = 'Drip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'link_type',
        'amazon_links',
        'message_type',
        'carriers',
        'team_id',
        'account_id',
        'reply_account_id',
        'message_group_id',
        'reply_message_group_id',
        'offer_id',
        'catalog_id',
        'repliers_catalog_id',
        'skip',
        'limit',
        'uuid',
        'type',
        'domain_group_id',
        'drip_wait_hours',
        'drip_skip_weekends',
        'drip_time_limit',
        'carriers',
        'rule_24_hours',
        'campaign_send_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carriers' => 'array',
        'team_id' => 'integer',
        'account_id' => 'integer',
        'reply_account_id' => 'integer',
        'message_group_id' => 'integer',
        'reply_message_group_id' => 'integer',
        'offer_id' => 'integer',
        'catalog_id' => 'integer',
        'skip' => 'integer',
        'limit' => 'integer',
        'amazon_links' => 'array',
        'drip_wait_hours' => 'integer',
        'drip_skip_weekends' => 'boolean',
        'carriers' => 'json',
		'rule_24_hours' => 'integer',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function account()
    {
        return $this->belongsTo(\App\Account::class);
    }

    public function replyAccount()
    {
        return $this->belongsTo(\App\Account::class);
    }

    public function messageGroup()
    {
        return $this->belongsTo(\App\MessageGroup::class);
    }

    public function replyMessageGroup()
    {
        return $this->belongsTo(\App\MessageGroup::class, 'reply_message_group_id');
    }

    public function offer()
    {
        return $this->belongsTo(\App\Offer::class);
    }

    public function catalog()
    {
        return $this->belongsTo(\App\Catalog::class);
    }

    public function repliersCatalog()
    {
        return $this->belongsTo(\App\Catalog::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function goodReplies()
    {
        return $this->hasMany(Reply::class)->good();
    }

    public function outbounds()
    {
        return $this->hasMany(Outbound::class);
    }

    public function pendingRepliesOutbounds()
    {
        return $this->outbounds()->pending()->reply();
    }

    public function sentReplyOutbounds()
    {
        return $this->outbounds()->sent()->reply();
    }

    public function deliveredReplyOutbounds()
    {
        return $this->outbounds()->reply()->delivered();
    }

    public function sentOutbounds()
    {
        return $this->outbounds()->sent();
    }

    public function failedOutbounds()
    {
        return $this->outbounds()->where('success', false);
    }

    public function deliveryReports()
    {
        return $this->hasManyThrough(DeliveryReport::class, Outbound::class);
    }

    public function domainGroup()
    {
        return $this->belongsTo(DomainGroup::class);
    }

    public function clicks()
    {
        return $this->hasMany(Click::class);
    }

    public function conversions()
    {
        return $this->hasMany(Conversion::class);
    }

    public function amountSpent()
    {
        return $this->outbounds()->succeeded()->sum('cost');
    }

    public function sameReplyAccount()
    {
        return $this->account_id === $this->reply_account_id;
    }

    public function scopeSending($query)
    {
        return $query->where('status', 'sending');
    }

    public function scopeDrip($query)
    {
        return $query->where('type', self::Drip);
    }

    public function scopeRegular($query)
    {
        return $query->where('type', self::Campaign);
    }

    public function pause()
    {
        $this->update(['status' => 'paused']);
    }

    public function getReplyAccount()
    {
        return $this->replyAccount->getRandomAccount();
    }

    public function getLink()
    {
        if ($this->usesAmazonLinks()) {
            return Arr::random($this->amazon_links);
        }

        return $this->offer->redirect_url;
    }

    public function usesAmazonLinks()
    {
        return $this->link_type === 'amazon';
    }

    public function generateAmazonLinks()
    {
        $this->amazon_links = resolve(AmazonLinkService::class)->generateLinks($this, 100);

        $this->save();
    }

    public function isDrip()
    {
        return $this->type === self::Drip;
    }

    public function sendDripMessageAt()
    {
        $date = now()->copy()->addHours($this->drip_wait_hours);

        if ($this->drip_skip_weekends && $date->isWeekend()) {
            $date = now()->next(Carbon::MONDAY);
        }

        return $date;
    }

    public function usesHash()
    {
        return $this->link_type == 'hash';
    }

    public function hasLimitedCarriers()
    {
        return !is_null($this->carriers) && count($this->carriers);
    }

    public function allowsCarrier($carrier)
    {
        if (!$this->hasLimitedCarriers()) {
            return true;
        }

        $carrier = $carrier instanceof Carrier ? $carrier : Carrier::find($carrier);

        return in_array($carrier->id, $this->carriers);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
