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
    public function updateCart(User $user, Product $product, array $params): ShoppingCart;

    public function processPayedCard(string $card): bool;
}
