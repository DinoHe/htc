<?php

namespace App\Listeners;

use App\Events\TradingOrder;
use App\Http\Controllers\Home\Trade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TradeTimeoutListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TradingOrder  $event
     * @return void
     */
    public function handle(TradingOrder $event)
    {
        while ($event->run){
            $timeout = 2*3600 - (time() - $event->time);
            if ($timeout <= 0){
                $trade = new Trade();
                $trade->finishPayConfirm();
            }
        }
    }
}
