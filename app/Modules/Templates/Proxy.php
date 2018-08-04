<?php

namespace App\Module\Templates;

use App\Jobs\IntegrateProxy;

class Proxy extends Module
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Download proxies
     *
     * @return void
     */
    public function downloadProxies()
    {
    }

    /**
     * Dispatch proxy integration job
     *
     * @param ProxyModel $proxy
     * @return void
     */
    public function integrateProxy(ProxyModel $proxy)
    {
        IntegrateProxy::dispatch($proxy);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getType()
    {
        return "proxy";
    }
}
