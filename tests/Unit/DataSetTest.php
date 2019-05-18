<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Turing\Helpers\DataSet;

class DataSetTest extends TestCase
{

    public function testToArrayReturnsIndexes()
    {
        $ds = new DataSet();
        $this->assertArrayHasKey('total', $ds->toArray());
        $this->assertArrayHasKey('items', $ds->toArray());
    }

    public function testSetTotalChangesToArray()
    {
        $ds = new DataSet();
        $ds->setTotal(1);

        $this->assertEquals(1, $ds->toArray()['total']);
    }

    public function testSetItemsChangesToArray()
    {
        $ds = new DataSet();
        $collection = collect(1);
        $ds->setItems($collection);

        $this->assertEquals($collection, $ds->toArray()['items']);
    }

    public function testSetterReturnsItselfObject()
    {
        $this->assertEquals($ds = new DataSet(), $ds->setTotal(1));
        $this->assertEquals($ds = new DataSet(), $ds->setItems(collect(1)));
    }
}
