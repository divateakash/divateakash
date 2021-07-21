<?php

namespace App;

use App\Account;
use App\Campaign;
use App\Catalog;
use App\Conversion;
use App\DomainGroup;
use App\DomainProvider;
use App\FileUpload;
use App\Lead;
use App\MessageGroup;
use App\Offer;
use App\Outbound;
use App\Provider;
use App\Reply;
use App\ReplyWord;
use App\Suppression;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'aws_key',
        'aws_secret',
        'aws_bucket',
        's3_type',
        'admin_id',
        'voluum_api_user',
        'voluum_api_key',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'admin_id' => 'integer',
    ];

    public function admin()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function catalogs()
    {
        return $this->hasMany(Catalog::class);
    }

    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function providers()
    {
        return $this->hasMany(Provider::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function messageGroups()
    {
        return $this->hasMany(MessageGroup::class);
    }

    public function outbounds()
    {
        return $this->hasMany(Outbound::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function domainProviders()
    {
        return $this->hasMany(DomainProvider::class);
    }

    public function domainGroups()
    {
        return $this->hasMany(DomainGroup::class);
    }

    public function suppressions()
    {
        return $this->hasMany(Suppression::class);
    }

    public function replyWords()
    {
        return $this->hasMany(ReplyWord::class);
    }

    public function conversions()
    {
        return $this->hasManyThrough(Conversion::class, Campaign::class);
    }

    public function getType()
    {
        return $this->s3_type == 'DO' ? 'DO' : 's3';
    }
}
