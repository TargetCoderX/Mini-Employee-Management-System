<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'position',
        'salary',
        'department_id',
    ];
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
