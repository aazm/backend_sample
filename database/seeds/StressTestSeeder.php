<?php

use Illuminate\Database\Seeder;

class StressTestSeeder extends Seeder
{
    const DEPARTMENTS_CNT = 10;
    const CATEGORIES_IN_DEP_CNT = 100;

    const PRODUCTS_CNT = 100000;
    const PRODUCT_IN_CATS = 10;

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
        for($i = 0 ; $i < self::PRODUCTS_CNT; $i++) {

            $product = factory(\Turing\Models\Product::class)->create();
            $collection = $collection->shuffle();

            $slice = $collection->slice(0, self::PRODUCT_IN_CATS);

            $pairs = [];
            foreach ($slice as $category) {
                $pairs[] = sprintf('(%d, %d)', $product->getKey(), $category->getKey());
            }
            $sql = "INSERT INTO product_category (product_id, category_id) values ".implode(',', $pairs);
            DB::insert($sql);
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
