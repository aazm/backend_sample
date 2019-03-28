<?php

namespace Turing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Turing\Rules\CategoryRule;
use Turing\Rules\DepartmentRule;

class ProductSearchRequest extends FormRequest
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
            'criteria' => 'array',
            'criteria.department' => ['numeric', new DepartmentRule()],
            'criteria.category' => ['numeric', new CategoryRule($this)],

            'criteria.name' => 'string',
            'criteria.description' => 'string',
            'offset' => 'numeric'
        ];
    }
}
