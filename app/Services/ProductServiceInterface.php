<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 27/03/2019
 * Time: 18:01
 */

namespace Turing\Services;

use Turing\Models\Product;
use Illuminate\Support\Collection;
use Turing\Helpers\DataSet;

interface ProductServiceInterface
{
    public function search(array $criteria, int $offset = 0): DataSet;

    public function getById($id): Product;

    public function getAvailableIds(): Collection;

    public function getCategories(): Collection;

    public function getDepartments(): Collection;


}