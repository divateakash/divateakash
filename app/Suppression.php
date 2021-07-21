<?php

namespace App;

use App\Lead;
use Illuminate\Database\Eloquent\Model;

class Suppression extends Model
{
    protected $fillable = [
        'phone', 'team_id',
    ];

    public function leads()
    {
        return Lead::query()->where('team_id', $this->team_id)->where('phone', $this->phone);
    }

    public function suppressLeads()
    {
        $this->leads()->update(['suppressed_at' => now()]);
    }

    public static function isSuppressed($teamId, $phone)
    {
        return self::where('team_id', $teamId)->where('phone', $phone)->exists();
    }
}
