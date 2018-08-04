<?php

namespace App\Module;

use App\Module\Templates\Module;
use App\Module\Templates\Proxy as ProxyModule;
use App\Models\Proxy;
use GuzzleHttp\Client;
use App\Jobs\IntegrateProxy;

class GatherProxyCom extends ProxyModule
{
    protected $origin = "gatherproxy.com";

    /**
     * Download proxies
     *
     * @return void
     */
    public function downloadProxies($country = "France")
    {
        $client = new Client();
        $response = $client->get('http://www.gatherproxy.com/fr/proxylist/country/?c=' . $country);
        foreach ($this->getRows($response->getBody()) as $proxyData) {
            $proxy = new Proxy();
            if ($this->parseProxyInformations($proxy, $proxyData)) {
                $proxy->origin = $this->origin;
                if ($proxy->saveProxy()) {
                    $this->integrateProxy($proxy);
                }
            }
        }
    }

    private function getRows($subject)
    {
        $pattern = "/<script type=\"text\/javascript\">(.*)<\/script>/sU";
        $matches = array();
        if (preg_match_all($pattern, $subject, $matches) > 0) {
            return $matches[1];
        }
        return $matches;
    }

    private function parseProxyInformations(Proxy $proxy, $subject)
    {
        $patternIp = "/PROXY_IP\":\"(.*)\"/sU";
        $patternPort = "/PROXY_PORT\":\"(.*)\"/sU";
        $patternAnon = "/PROXY_TYPE\":\"(.*)\"/sU";

        $matchesIp = array();
        $matchesPort = array();
        $matchesAnon = array();
        if (preg_match($patternIp, $subject, $matchesIp)
            && preg_match($patternPort, $subject, $matchesPort)
            && preg_match($patternAnon, $subject, $matchesAnon)) {
            $proxy->ip = $matchesIp[1];
            $proxy->port = base_convert($matchesPort[1], 16, 10);
            $proxy->country_code = 'FR';
            $proxy->anonymity = $matchesAnon[1];
            $proxy->type = Proxy::TYPE_HTTP;
            return true;
        }
        return false;
    }
}
