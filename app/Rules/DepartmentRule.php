<?php

namespace Turing\Rules;

use Illuminate\Contracts\Validation\Rule;
use Turing\Models\Department;

class DepartmentRule implements Rule
{
    private $departments;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->departments = cache()->remember('department:ids', config('turing.cache_ttl'), function(){
            return \Turing\Models\Department::select('department_id')->get()->pluck('department_id')->toArray();
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
        return in_array($value, $this->departments);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Department does not exists';
    }
}
