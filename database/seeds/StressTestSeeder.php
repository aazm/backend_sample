<?php

use Illuminate\Database\Seeder;

class StressTestSeeder extends Seeder
{
    const DEPARTMENTS_CNT = 50;
    const CATEGORIES_IN_DEP_CNT = 10;

    const PRODUCTS_CNT = 100000;
    const PRODUCT_IN_CATS = 20;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(is_array(config('telescope')) && config('telescope.enabled') == true) {
            throw new \RuntimeException('Disable telescope first');
        }
        
        $departments  = $this->seedDepartments();
        $categories = $this->seedCategories($departments);
        $this->seedProducts($categories);
    }

    private function seedProducts(\Illuminate\Support\Collection $collection)
    {
        $offset = 0;
        for($i = 0 ; $i < self::PRODUCTS_CNT; $i++) {

            $product = factory(\Turing\Models\Product::class)->create();

            $pairs = [];

            $collection->shuffle();
            $offset = 0;
            foreach ($collection->slice($offset, self::PRODUCT_IN_CATS) as $category) {

                $pairs[] = sprintf('(%d, %d)', $product->getKey(), $category->getKey());

                //todo: understand why generates an error
                /*
                \Turing\ProductCategory::create([
                   'product_id' => $product->getKey(),
                   'category_id' => $category->getKey()
                ]);*/
            }

            // looks like working solution
            DB::insert("INSERT INTO product_category (product_id, category_id) values ".implode(',', $pairs));

            if($offset == self::PRODUCT_IN_CATS - $collection->count()) {
                $collection->shuffle();
                $offset = 0;
            } else  {
                $offset += self::PRODUCT_IN_CATS;
            }

        }
    }

    private function seedDepartments()
    {
        $collection = collect();
        for($i = 0; $i < self::DEPARTMENTS_CNT; $i++) {
            $collection->add(factory(\Turing\Models\Department::class)->create());
        }

        return $collection;
    }

    private function seedCategories(\Illuminate\Support\Collection $departments)
    {
        $collection = collect();
        foreach($departments as $department) {
            for($i = 0; $i < self::CATEGORIES_IN_DEP_CNT; $i++) {
                $collection->add(
                    factory(\Turing\Models\Category::class)->create(['department_id' => $department->getKey()]));
            }
        }

        return $collection;
    }
}
