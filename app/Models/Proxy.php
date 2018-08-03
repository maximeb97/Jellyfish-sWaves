<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Proxy extends Model
{
    const TYPE_HTTP = "http";
    const TYPE_HTTPS = "https";
    const TYPE_SOCKS4 = "socks4";
    const TYPE_SOCKS5 = "socks5";

    // TODO: add a speed field
    protected $fillable = [
        'ip',
        'port',
        'country_code',
        'anonymity',
        'type',
        'is_working',
        'checked_at',
        'origin'
    ];

    /**
     * Save the proxy
     *
     * @return void
     */
    public function saveProxy()
    {
        // TODO: Verify that the address isn't already existing in the database
        $this->save();
        return true;
    }

    /**
     * Check if the proxy is working
     *
     * @return void
     */
    public function checkProxy($checkAgainIfExists = true)
    {
        $proxy = $this;
        if (!$this->isIp($proxy->ip)
            || !$this->isIp($proxy->port)
            || !$this->isKnownType($proxy->type)) {
            return false;
        }
        if (($tmpProxy = Proxy::where('ip', $this->ip)->where('port', $this->port)->whereNotNull('is_working')->first()) != null) {
            $proxy = $tmpProxy;
            if (!$checkAgainIfExists) {
                return $proxy->is_working;
            }
        }
        $proxy->checked_at = Carbon::now();
        if ($fp = @fsockopen($proxy->ip, $proxy->port, $errCode, $errStr, env('PROXY_TIMEOUT_IN_MS', 10000))) {
            fclose($fp);
            $proxy->is_working = true;
        } else {
            $proxy->is_working = false;
        }
        $proxy->saveProxy();
        return $proxy->is_working;
    }

    protected function isIp(string $ip)
    {
        // TODO: check if string passed is an ip
        return true;
    }

    protected function isPort(string $port)
    {
        // TODO: check if string passed is a port
        return true;
    }

    /**
     * check if the type exist
     *
     * @param string $type
     * @return boolean
     */
    protected function isKnownType(string $type)
    {
        // TODO: check if the type is known
        if ($type == self::TYPE_HTTP
            || $type == self::TYPE_HTTPS) {
                return true;
        }
        return false;
    }
}
