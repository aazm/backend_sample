<?php

namespace Turing;

use CoenJacobs\EloquentCompositePrimaryKeys\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasCompositePrimaryKey;

    protected $table = 'product_category';
    protected $primaryKey = ['product_id', 'category_id'];
}
