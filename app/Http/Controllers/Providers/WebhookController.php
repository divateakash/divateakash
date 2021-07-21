<?php

namespace App\Http\Controllers\Providers;

use App\Http\Controllers\Controller;
use App\Jobs\AddLeadToCatalog;
use App\Jobs\ProcessPendingOutbound;
use App\Jobs\SuppressLeadsByPhone;
use App\Outbound;
use App\Reply;
use App\Support\BadWords;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function __invoke($provider)
    {
        if (env('LOG_REQUESTS')) {
            logger(request()->all());
        }

        $data = $this->getData($provider);

        $outbound = Outbound::query()
            ->where('from', $data['to'])
            ->where('to', $data['from'])
            ->latest()
            ->first();

        if (!$outbound) {
            return response()->json([
                'message' => 'Message not found',
            ], 404);
        }

        $reply = Reply::create([
            'content' => Str::limit($data['message'], 250, ''),
            'from' => $data['from'],
            'to' => $data['to'],
            'outbound_id' => $outbound->id,
            'campaign_id' => $outbound->campaign_id,
            'account_id' => $outbound->account_id,
            'team_id' => $outbound->team_id,
            'stop' => $this->isStopReply($data['message'], $outbound->team),
            'good' => $this->isGoodReply($data['message'], $outbound->team),
        ]);

        if ($reply->stop) {
            SuppressLeadsByPhone::dispatch($reply->from, $outbound->team);
        }

        if (!$reply->stop && $this->shouldSendReplyMessage($reply)) {
            $this->replicateAndSendOutbound($outbound, $reply);
        }

        $this->moveToRepliersList($outbound, $reply);

        return response()->json([
            'success' => true,
        ]);
    }

    public function shouldSendReplyMessage($reply)
    {
        return $reply->campaign->message_type == 'keyword_reply'
        && $reply->outbound->replies()->count() <= 1
        && !$reply->outbound->isReply()
        && $reply->good;
    }

    public function moveToRepliersList(Outbound $outbound, Reply $reply)
    {
        if (!$reply->good || !($repliersCatalog = $outbound->campaign->repliersCatalog)) {
            return;
        }

        AddLeadToCatalog::dispatch($repliersCatalog, $outbound->lead->toArray());
    }

    public function replicateAndSendOutbound($outbound, $reply)
    {
        $campaign = $outbound->campaign;
        $replyOutbound = $outbound->replicate([
            'success', 'error', 'send_at', 'sent_at', 'response', 'content', 'message_id', 'message_group_id', 'account_id',
        ]);

        $replyOutbound->fill([
            'from' => $campaign->sameReplyAccount() ? $outbound->from : null,
            'to' => $reply->from,
            'processed' => false,
            'send_at' => now(),
            'account_id' => optional($campaign->getReplyAccount())->id,
            'message_group_id' => $campaign->reply_message_group_id,
            'reply_id' => $reply->id,
            'domain_group_id' => $campaign->domain_group_id,
        ]);

        $replyOutbound->save();

        ProcessPendingOutbound::dispatch($replyOutbound, $replyOutbound->account);
    }

    protected function getData($provider)
    {
        return [
            'txtria' => [
                'from' => number(request('From')),
                'to' => number(request('To')),
                'messageId' => request('message_id'),
                'message' => request('Body', ''),
            ],
            'gorilla' => [
                'from' => number(request('from')),
                'to' => number(request('to')),
                'messageId' => null,
                'message' => request('body', ''),
            ],
            'twilio' => [
                'from' => number(request('From')),
                'to' => number(request('To')),
                'messageId' => request('MessageSid'),
                'message' => request('Body', ''),
            ],
            'textcalibur' => [
                'from' => number(request('From')),
                'to' => number(request('To')),
                'messageId' => null,
                'message' => request('Body', ''),
            ],
            'simpletexting' => [
                'from' => number(request('from')),
                'to' => number(request('to')),
                'messageId' => null,
                'message' => request('text'),
            ],
        ][$provider] ?? null;
    }

    public function isStopReply($message, $team)
    {
        if (!config('konnectext.global_words')) {
            $words = $team->replyWords()->bad()->get()->map->word->all();
        } else {
            $words = bad_words();
        }

        return BadWords::isPresent($words, $message);
    }

    public function isGoodReply($message, $team)
    {
        if (config('konnectext.global_words')) {
            return true;
        }

        $words = $team->replyWords()->good()->get()->map->word->all();

        return Str::contains(Str::lower($message), $words);
    }
}
