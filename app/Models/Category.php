<?php

namespace Turing\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'category_id';

    public function department()
    {
        return $this->belongsTo(Department::class, 'category_id', 'department_id');
    }

    public function isIn(int $department_id)
    {
        return $department_id == $this->getAttribute('department_id');
    }
}
