<?php

namespace App\Services;

use App\Domain;
use App\Exceptions\FailedToUpdateDomainDnsException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NamecheapManager
{
    public $user;

    public $password;

    public $apiUrl = 'https://api.namecheap.com/xml.response';

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getDomains()
    {
        return Http::get($this->apiUrl, [
            'ApiUser' => $this->user,
            'ApiKey' => $this->password,
            'UserName' => $this->user,
            'Command' => 'namecheap.domains.getList',
            'ClientIp' => '179.223.248.82',
        ]);
    }

    public function updateDNS(Domain $domain)
    {
        $request = Http::get($this->apiUrl, [
            'apiuser' => $this->user,
            'ApiKey' => $this->password,
            'UserName' => $this->user,
            'ClientIp' => '179.223.248.82',
            'Command' => 'namecheap.domains.dns.setHosts',
            'SLD' => $domain->sld(),
            'TLD' => $domain->tld(),
            'HostName1' => '@',
            'RecordType1' => 'A',
            'Address1' => config('konnectext.domains_ip'),
            'TTL1' => 100,
        ]);

        if ($this->isSuccess($request->body())) {
            return true;
        }

        throw new FailedToUpdateDomainDnsException($this->getError($request->body()));
    }

    /**
     * Get the first error from a failed Namecheap response.
     *
     * @param  string $body
     * @return string
     */
    protected function getError($body)
    {
        $body = (string) $body;

        $xml = simplexml_load_string($body);

        $error = Arr::first((array) $xml->Errors);

        return is_array($error) ? $error[0] : $error;
    }

    /**
     * Determine if the API call was a success.
     *
     * @param  string $body
     * @return boolean
     */
    protected function isSuccess($body)
    {
        $body = (string) $body;

        $xml = simplexml_load_string($body);

        $status = (array) $xml['Status'];
        $status = $status[0];

        return $status === 'OK';
    }
}
