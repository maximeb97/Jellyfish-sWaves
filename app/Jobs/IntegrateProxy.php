<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;
use App\Models\Proxy;
use App\Models\Log;

class IntegrateProxy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    protected $proxy;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Proxy $proxy)
    {
        $this->proxy = $proxy;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->proxy->checkProxy(false)) {
            Log::log(Log::TYPE_SUCCESS,
                $this->proxy->ip . ':' . $this->proxy->port . ' integrated',
            $this, 'proxy');
        }
    }

    /**
     * Job failed
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::log(Log::TYPE_ERROR, $exception->getMessage(), $this, 'proxy');
    }
}
