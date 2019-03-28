<?php

namespace Turing\Rules;

use Illuminate\Contracts\Validation\Rule;
use Turing\Http\Requests\ProductSearchRequest;
use Turing\Services\ProductServiceInterface;

class CategoryRule implements Rule
{

    const MESSAGE_NOT_EXISTS = 'Category does not exists';
    const MESSAGE_INVALID_PAIR = 'Invalid department&category combination';

    /**
     * @var array Categories ids
     */
    private $categories;

    /**
     * @var ProductSearchRequest instance
     */
    private $request;

    /**
     * @var string Error message
     */
    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(ProductSearchRequest $request)
    {
        $this->request = $request;

        $this->categories = resolve(ProductServiceInterface::class)
            ->getCategories()
            ->groupBy('category_id');

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
        if(!isset($this->categories[$value])) {
            $this->message = self::MESSAGE_NOT_EXISTS;

            return false;

        } else if(!isset($this->request->get('criteria')['department'])) {

            return true;

        } else {

            $department = $this->request->get('criteria')['department'];
            $category = $this->categories[$value]->first();

            if(!$category->isIn($department)) {
                $this->message = self::MESSAGE_INVALID_PAIR;
                return false;
            }

            return true;

        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
