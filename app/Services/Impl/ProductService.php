<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 27/03/2019
 * Time: 18:01
 */

namespace Turing\Services\Impl;

use Illuminate\Support\Collection;
use Turing\Helpers\DataSet;
use Turing\Helpers\EmptyDataSet;
use Turing\Models\Category;
use Turing\Models\Department;
use Turing\Models\Product;
use Turing\Services\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    public function search(array $criteria, int $offset = 0): DataSet
    {
        $builder = $this->getQuery($criteria);
        $ids = $builder->get()->pluck('product_id');

        if (!$ids->count()) return new EmptyDataSet();
        if ($offset >= $ids->count()) return new EmptyDataSet();

        $product = new Product();

        $builder = $product->newModelQuery();
        $builder->orderBy($product->getKeyName());
        $builder->whereIn($product->getKeyName(), $ids->toArray());
        $builder->take(config('turing.items_per_page'));
        $builder->skip($offset);

        $dataSet = (new DataSet())
            ->setTotal($ids->count())
            ->setItems($builder->get());

        return $dataSet;
    }

    private function getQuery(array $criteria)
    {
        $product = new Product();

        $builder = $product->newModelQuery();
        $builder->select($product->getKeyName());

        if(isset($criteria['name'])) {
            $builder->where('name', 'like', $criteria['name'].'%');
        }

        if(isset($criteria['description'])) {
            $builder->where('description', 'like', '%' . $criteria['description'] . '%');
        }

        if(isset($criteria['category']) || isset($criteria['department'])) {

            $builder->whereHas('categories', function ($query) use ($criteria) {

                if(isset($criteria['category'])) {
                    $query->where('category.category_id', $criteria['category']);
                }

                if(isset($criteria['department'])) {
                    $query->where('category.department_id', $criteria['department']);
                }

            });

        }

        $builder->orderBy($product->getKeyName());

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

