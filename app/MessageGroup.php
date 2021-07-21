<?php

namespace App;

use App\Enums\MessageGroupType;
use App\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MessageGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'team_id',
        'type',
        'single_message_only',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'team_id' => 'integer',
        'single_message_only' => 'boolean',
    ];

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function activeMessages()
    {
        return $this->messages()->withoutTrashed();
    }

    public function deletedMessages()
    {
        return $this->hasMany(Message::class)->onlyTrashed();
    }

    public function randomMessage()
    {
        if ($this->single_message_only) {
            return $this->messages()->first();
        }

        return $this->messages()->inRandomOrder()->first();
    }

    public function scopeType($query, $type)
    {
        $type = Str::camel($type);

        return $query->where('type', $type);
    }

    public function isFirstMessage()
    {
        return $this->type === MessageGroupType::First;
    }

    public function isReplyMessage()
    {
        return $this->type === MessageGroupType::Reply;
    }

    public function availableMessagesCount()
    {
        return 15000 - $this->messages()->count();
    }
}
