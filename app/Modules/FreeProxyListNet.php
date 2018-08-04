<?php

namespace App\Module;

use App\Module\Templates\Module;
use App\Module\Templates\Proxy as ProxyModule;
use App\Models\Proxy;
use GuzzleHttp\Client;
use App\Jobs\IntegrateProxy;

class FreeProxyListNet extends ProxyModule
{
    protected $origin = "free-proxy-list.net";

    /**
     * Download proxies
     *
     * @return void
     */
    public function downloadProxies()
    {
        $client = new Client();
        $response = $client->get('https://free-proxy-list.net/');
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
        $pattern = "/<tr>(.*)<\/tr>/sU";
        $matches = array();
        if (preg_match_all($pattern, $subject, $matches) > 0) {
            return $matches[1];
        }
        return $matches;
    }

    private function parseProxyInformations(Proxy $proxy, $subject)
    {
        $pattern = "/<td>(.*)<\/td>/sU";
        $patternHttps = "/<td class='hx'>(.*)<\/td>/sU";
        $matches = array();
        if (preg_match_all($pattern, $subject, $matches) > 0
            && count($matches[1]) == 4) {
            $proxy->ip = $matches[1][0];
            $proxy->port = $matches[1][1];
            $proxy->country_code = $matches[1][2];
            $proxy->anonymity = $matches[1][3];
            if (!preg_match($patternHttps, $subject, $matches)) {
                return false;
            }
            if ($matches[1] == "yes") {
                $proxy->type = Proxy::TYPE_HTTPS;
            } else {
                $proxy->type = Proxy::TYPE_HTTP;
            }
            return true;
        }
        return false;
    }
}
