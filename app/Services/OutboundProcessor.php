<?php

namespace App\Services;

use App\Account;
use App\Exceptions\LeadReceivedMessageRecentlyException;
use App\Exceptions\NoDomainsAvailableException;
use App\Exceptions\Sending\NoMessageAvailableException;
use App\Exceptions\Sending\OutboundOutsideSendingHoursException;
use App\Messaging\Exceptions\MessageFailedToSendException;
use App\Messaging\Providers\Provider;
use App\Outbound;
use App\Services\OutboundService;
use App\Support\Spintax;

class OutboundProcessor
{
    public function process(Outbound $outbound, Provider $provider, Account $account = null, $number = null)
    {
        if ($outbound->sent_at || is_null($account)) {
            return;
        }

        throw_if(!$outbound->lead->underSendHours(now()), OutboundOutsideSendingHoursException::class);

        $message = $outbound->getRandomMessage();
        throw_if(!$message, NoMessageAvailableException::class);
		
		$check_24_rule = $outbound->campaign->rule_24_hours;
		if($check_24_rule == 1){
			$this->checkIfLeadReceivedAMessageRecently($outbound);
		}        

        [$username, $password] = [$account->provider->username, $account->provider->password];

        // If we are passing a number already, it means we are sending through the scheduled job,
        // therefore we do not need to fetch a number again.
        $number = $number ?? $this->getNumber($account, $outbound->from);
		
        $domain = $outbound->getLink();
        $messageContent = $this->parseMessage($message, $outbound, $domain);

        $outbound->fill([
            'from' => $number->number,
            'processed' => true,
            'sent_at' => now(),
            'content' => $messageContent,
            'message_group_id' => $message->message_group_id,
            'message_id' => $message->id,
            'number_id' => $number->id,
            'domain_id' => optional($domain)->id,
        ]);

        try {
            $providerMessage = $provider->send(
                $username,
                $password,
                $number->number,
                $outbound->to,
                $messageContent
            );
        } catch (MessageFailedToSendException $exception) {
            $this->handleError($exception, $message, $outbound);

            return tap($outbound->fill([
                'error' => $exception->getMessage(),
                'response' => $exception->getMessage(),
                'success' => false,
            ]))->save();
        }

        return tap($outbound->fill([
            'success' => true,
            'response' => $providerMessage->id,
        ]))->save();
    }

    public function getNumber($account, $from = null)
    {
        if ($from) {
            return $account->numbers()->where('number', $from)->firstOr(fn() => $this->getNumber($account));
        }

        return $account->randomNumber();
    }

    public function getDomain(Outbound $outbound)
    {
        if ($outbound->domain_group_id) {
            $domain = $outbound->randomDomain();
            throw_if(!$domain, new NoDomainsAvailableException);

            return $domain;
        }

        return null;
    }

    public function parseMessage($message, $outbound, $domain = null)
    {
        $content = $message->content;
        $lead = $outbound->lead;
		
        $content = str_replace('[FIRST_NAME]', $lead->first_name, $content);
        $content = str_replace('[LAST_NAME]', $lead->last_name, $content);
        $content = str_replace('[FIRSTNAME]', $lead->first_name, $content);
        $content = str_replace('[LASTNAME]', $lead->last_name, $content);
        $content = str_replace('[CITY]', $lead->city, $content);
        $content = str_replace('[STATE]', $lead->region, $content);
        $content = str_replace('[EMAIL]', $lead->email, $content);
        $content = str_replace('[PHONE]', $lead->phone, $content);
        $content = str_replace('[DATE]', today()->toDateString(), $content);
        //$content = str_replace('[LINK]', $outbound->getLink($domain), $content);
        $content = str_replace('[LINK]', $domain, $content);

        $content = Spintax::parse($content);

        if ($messageSuffix = config('konnectext.message_suffix')) {
            $content .= " {$messageSuffix}";
        }

        return Spintax::parse($content);
    }

    public function handleError($exception, $message, $outbound)
    {
        if ($exception->getMessage() == 'Message cannot be sent, banned word found') {
            if (resolve(OutboundService::class)->bannedWordsRate($outbound->campaign_id, $outbound->message_id) > 10) {
                $message->deleteWithReason('Banned word found');
            }

        } elseif ($exception->getMessage() == 'Sending is banned to this number') {
            $outbound->lead->globallySuppress();
        }
    }

    protected function checkIfLeadReceivedAMessageRecently($outbound)
    {
        if ($outbound->isReply() || (config('konnectext.24h_limit') == false)) {
            return false;
        }

        if (resolve(OutboundService::class)->sentMessageTo(
            $outbound->team_id,
            $outbound->to,
            now()->subHours(24)
        )) {
            throw new LeadReceivedMessageRecentlyException;
        }
    }
}
