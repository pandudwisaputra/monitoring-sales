<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccess implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $commission;

    public function __construct($commission)
    {
        $this->commission = $commission;
    }

    public function broadcastOn()
    {
        return new Channel('payments');
    }

    public function broadcastAs()
    {
        return 'payment.success';
    }
}