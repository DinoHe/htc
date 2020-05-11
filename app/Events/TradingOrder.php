<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class TradingOrder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $time;
    public $orderId;
    public $run;
    public $request;
    /**
     * Create a new event instance.
     * @param $time
     * @param $orderId
     * @param $run
     * @param Request $request
     * @return void
     */
    public function __construct($time,$orderId,$run,Request $request)
    {
        $this->time = $time;
        $this->orderId = $orderId;
        $this->run = $run;
        $this->request = $request;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
