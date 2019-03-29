<?php

namespace Turing\Events\ShoppingCart;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Turing\Models\ShoppingCart;

class BuyNowProductAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var ShoppingCart $cart
     */
    public $cart;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ShoppingCart $cart)
    {
        $this->cart = $cart;
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
