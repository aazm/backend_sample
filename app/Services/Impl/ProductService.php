<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 27/03/2019
 * Time: 18:01
 */

namespace Turing\Services\Impl;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Turing\Helpers\DataSet;
use Turing\Helpers\EmptyDataSet;
use Turing\Models\Category;
use Turing\Models\Department;
use Turing\Models\Product;
use Turing\Services\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    //todo: think about this approach
    CONST PLACEHODER_VALS_MAX = 65500;

    public function search(array $criteria, int $offset = 0): DataSet
    {
        $builder = $this->getQuery($criteria);
        $ids = $builder->get()->pluck('product_id');

        if (!$ids->count()) return new EmptyDataSet();
        if ($offset >= $ids->count()) return new EmptyDataSet();

        $product = new Product();

        $builder = $product->newModelQuery();
        $builder->orderBy($product->getKeyName());
        $builder->whereIn($product->getKeyName(), $ids->slice(0, self::PLACEHODER_VALS_MAX));

        $builder->take(config('turing.items_per_page'));
        $builder->skip($offset);

        Log::warning($builder->toSql());

        $dataSet = (new DataSet())
            ->setTotal($ids->count())
            ->setItems($builder->get());

        return $dataSet;
    }

    private function getQuery(array $criteria)
    {
        $builder = DB::table('product')
            ->select('product.product_id')
            ->orderBy('product.product_id', 'asc');

        if(isset($criteria['name'])) {
            $builder->where('product.name', 'like', $criteria['name'].'%');
        }

        if(isset($criteria['description'])) {
            $builder->where('product.description', 'like', '%' . $criteria['description'] . '%');
        }

        if(isset($criteria['category']) || isset($criteria['department'])) {
            $builder->join('product_category', 'product.product_id', '=', 'product_category.product_id');
            $builder->join('category', 'category.category_id', '=', 'product_category.category_id');

            if(isset($criteria['category'])) {
                $builder->where('category.category_id', $criteria['category']);
            }

            if(isset($criteria['department'])) {
                $builder->where('category.department_id', $criteria['department']);
            }
        }

        return $builder;
    }

    public function getById($id): Product
    {
        return Product::with('categories')->findOrFail($id);
    }

    public function getAvailableIds(): Collection
    {
        return Product::select('product_id')->get();
    }

    public function getCategories(): Collection
    {
        return Category::all();
    }

    public function getDepartments(): Collection
    {
        return Department::all();
    }
}

