<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 27/03/2019
 * Time: 18:52
 */

namespace Turing\Helpers;
use Illuminate\Support\Collection;

class DataSet
{
    /** @var int  */
    private $total;

    /** @var  Collection */
    private $items;

    public function setTotal(int $total)
    {
        $this->total = $total;
        return $this;
    }

    public function setItems(Collection $items)
    {
        $this->items = $items;
        return $this;
    }

    public function toArray()
    {
        return ['total' => $this->total, 'items' => $this->items];
    }

}