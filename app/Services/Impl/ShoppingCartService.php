<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 29/03/2019
 * Time: 13:30
 */

namespace Turing\Services\Impl;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Turing\Events\ShoppingCart\BuyNowProductAdded;
use Turing\Models\Product;
use Turing\Models\ShoppingCart;
use Turing\Services\ShoppingCartServiceInterface;
use Turing\User;

class ShoppingCartService implements ShoppingCartServiceInterface
{
    /**
     * @inheritdoc
     */
    public function updateCart(User $user, Product $product, array $params): ShoppingCart
    {
        try {

            DB::beginTransaction();

            $cart = ShoppingCart::create([
                'attributes' => $params['attributes'],
                'quantity' => $params['quantity'],
                'buy_now' => $params['buy_now'],
                'added_on' => \Carbon\Carbon::now(),
                
                'cart_id' => ShoppingCart::getOrCreateCartId($user),
                'customer_id' => $user->getKey(),
                'product_id' => $product->getKey(),

            ]);

            DB::commit();

            if($cart->buy_now) {

                event(new BuyNowProductAdded($cart));

            }

            return $cart;

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Unable to add product into shopping cart', ['e' => $e]);

            throw $e;

        }
    }

}