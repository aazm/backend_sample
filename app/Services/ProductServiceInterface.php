<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 27/03/2019
 * Time: 18:01
 */

namespace Turing\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Turing\Models\Product;
use Illuminate\Support\Collection;
use Turing\Helpers\DataSet;

interface ProductServiceInterface
{
    /**
     * Search by products
     *
     * Returns DataSet object or EmptyDataSet object if no items were found
     * or given offset is out of the range
     *
     * @param array $criteria
     * @param int $offset
     * @return DataSet
     */
    public function search(array $criteria, int $offset = 0): DataSet;

    /**
     * Returns Product with it's categories by given id
     *
     * @throws ModelNotFoundException $e
     * @param $id
     * @return Product
     */
    public function getById($id): Product;

    /**
     * Returns collection of available for selling products
     *
     * @return Collection
     */
    public function getAvailableIds(): Collection;

    /**
     * Returns Collection of categories which are available for searching
     *
     * @return Collection
     */
    public function getCategories(): Collection;

    /**
     * Returns Collection of departments which are available for searching
     *
     * @return Collection
     */
    public function getDepartments(): Collection;


}