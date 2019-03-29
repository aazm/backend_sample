<?php

namespace Turing\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ShippingRegionRule implements Rule
{
    private $map;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->map = cache()->remember('shipping:regions:ids', config('turing.cache_ttl'), function(){
            return DB::table('shipping_region')
                ->select('shipping_region_id')
                ->get()
                ->pluck('shipping_region_id')
                ->flip()
                ->toArray();
        });
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return isset($this->map[$value]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Wrong shipping region id';
    }
}
