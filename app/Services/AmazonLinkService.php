<?php

namespace App\Services;

use App\Campaign;
use App\Team;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AmazonLinkService
{
    public function generateLinks(Campaign $campaign, $amount = 3)
    {
        return collect(range(1, $amount))->map(function ($index) use ($campaign) {
            return $this->generateLink($campaign);
        });
    }

    public function generateLink(Campaign $campaign)
    {
        $redirect = $this->buildRedirectUrl($campaign);
        $this->setConfig($campaign->team);

        $view = view('amazon.redirect', ['redirect' => $redirect]);
        $filename = $this->getRandomFilename();
        $type = $campaign->team->getType();
        logger(config("filesystems.disks.{$type}.bucket"));
        logger($type);
        Storage::disk($type)->put($filename, $view->render(), 'public');

        return $this->getLink($filename, $type);
    }

    public function getRandomFilename()
    {
        $prefix = [
            'help',
            'page',
            'view',
            'index',
            'find',
            'home',
            'article',
        ][rand(0, 5)];

        return $prefix . Str::random(7) . '.html';
    }

    public function buildRedirectUrl(Campaign $campaign)
    {
        $redirect = $campaign->offer->redirect_url;

        $query = parse_url($campaign->offer->redirect_url, PHP_URL_QUERY);

        if ($query) {
            $redirect .= "&provider=amazon&campaignkey={$campaign->uuid}";
        } else {
            $redirect .= "?provider=amazon&campaignkey={$campaign->uuid}";
        }

        return $redirect;
    }

    public function getLink($filename, $type)
    {
        $bucket = config("filesystems.disks.{$type}.bucket");
        $region = config("filesystems.disks.{$type}.region");
        $domain = config("filesystems.disks.{$type}.domain");

        if ($type === 'DO') {
            return "{$bucket}.{$region}.{$domain}/{$filename}";
        } else {
            return "{$bucket}.s3.{$region}.{$domain}/{$filename}";
        }
    }

    public function setConfig(Team $team)
    {
        $type = $team->getType();

        config()->set("filesystems.disks.{$type}.bucket", $team->aws_bucket);
        config()->set("filesystems.disks.{$type}.key", $team->aws_key);
        config()->set("filesystems.disks.{$type}.secret", $team->aws_secret);
    }
}
