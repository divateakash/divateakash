<?php

namespace App\Services;

use App\Outbound;

class OutboundService
{
    /**
     * Determines if a message was sent to a number at a given timeframe.
     *
     * @param  integer $teamId
     * @param  string $phoneNumber
     * @param  \Carbon\Carbon|null $since
     * @return boolean
     */
    public function sentMessageTo($teamId, $phoneNumber, $since = null)
    {
        $since = $since ?? now()->subHours(24);

        return Outbound::where('team_id', $teamId)->where('to', $phoneNumber)->where('sent_at', '>=', $since)->whereNull('reply_id')->exists();
    }

    public function bannedWordsRate($campaignId, $messageId)
    {
        $sentOutboundsCount = Outbound::where('campaign_id', $campaignId)->where('message_id', $messageId)->sent()->count();

        if ($sentOutboundsCount < 100) {
            return 0;
        }

        $bannedWordsCount = Outbound::where('campaign_id', $campaignId)->where('message_id', $messageId)->where('response', 'Message cannot be sent, banned word found')->count();

        return percentage($bannedWordsCount, $sentOutboundsCount);
    }
}
