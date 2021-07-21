<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'delete_reason',
        'message_group_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'message_group_id' => 'integer',
    ];

    public function messageGroup()
    {
        return $this->belongsTo(\App\MessageGroup::class);
    }

    public function deleteWithReason($reason)
    {
        $this->update(['delete_reason' => $reason]);

        $this->delete();
    }
}
