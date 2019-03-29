<?php

namespace Turing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Turing\Rules\ShippingRegionRule;

class CustomerProfileUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'credit_card' => 'string',
            'address_1' => 'string|max:100',
            'address_2' => 'string|max:100',
            'city' => 'string|max:100',
            'region' => 'string|max:100',
            'postal_code' => 'string|max:100',
            'country' => 'string|max:100',
            'day_phone' => 'string|max:100',
            'eve_phone' => 'string|max:100',
            'mob_phone' => 'string|max:100',
            'shipping_region_id' => ['integer', new ShippingRegionRule()]
        ];
    }
}
