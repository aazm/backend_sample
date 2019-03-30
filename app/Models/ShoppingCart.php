<?php

namespace Turing\Models;

use Illuminate\Database\Eloquent\Model;
use Turing\User;

class ShoppingCart extends Model
{
    public $timestamps = false;

    protected $table = 'shopping_cart';
    protected $primaryKey = 'item_id';
    protected $guarded = ['item_id'];

    public static function getOrCreateCartId(User $user): string
    {
        $record = ShoppingCart::where('customer_id', $user->getKey())->select('cart_id')->first();
        if($record) return $record->cart_id;

        return uniqid($user->getKey().'_');
    }
}
