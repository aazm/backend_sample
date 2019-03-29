<?php

namespace Turing\Rules;

use Illuminate\Contracts\Validation\Rule;
use Turing\Services\ProductServiceInterface;

class ProductRule implements Rule
{

    private $map;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->map = resolve(ProductServiceInterface::class)
            ->getAvailableIds()
            ->pluck('product_id')
            ->flip();
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
        return 'Product does not exist.';
    }
}
