<?php

namespace Turing\Listeners\ShoppingCart;

use Illuminate\Support\Facades\Log;
use Turing\Events\ShoppingCart\BuyNowProductAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BuyNowProductAddedNotifyLog
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
     * @param  BuyNowProductAdded  $event
     * @return void
     */
    public function handle(BuyNowProductAdded $event)
    {
        Log::info('Buy now product was added into cart :' . $event->cart->cart_id);
    }
}
