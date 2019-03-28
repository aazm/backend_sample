<?php

namespace Turing\Models;

use Illuminate\Database\Eloquent\Model;
use Turing\ProductCategory;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_id';

    public function categories()
    {
        return $this->hasManyThrough(
            Category::class,
            ProductCategory::class,
            'product_id',
            'category_id',
            'product_id',
            'category_id'
            );
    }
}
