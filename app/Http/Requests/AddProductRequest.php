<?php

namespace Turing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Turing\Rules\ProductRule;

class AddProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => ['required', 'numeric', new ProductRule()],
            'attributes' => 'required|string|max:1000',
            'quantity' => 'required|numeric|min:1',
            'buy_now' => 'required|boolean',
        ];
    }
}
