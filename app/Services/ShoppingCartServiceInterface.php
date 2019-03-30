<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 29/03/2019
 * Time: 13:30
 */

namespace Turing\Services;

use Illuminate\Support\Collection;
use Turing\Models\Product;
use Turing\Models\ShoppingCart;
use Turing\User;

interface ShoppingCartServiceInterface
{
    /**
     * Adds into product with params into shopping cart.
     *
     * This methods managing cart creating if no one has been created yet.
     * Fires event BuyNowProductAdded.
     *
     * @throws \Exception
     * @param User $user
     * @param Product $product
     * @param array $params
     * @return ShoppingCart
     */
    public function updateCart(User $user, Product $product, array $params): ShoppingCart;

}
