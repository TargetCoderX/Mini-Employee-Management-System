<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Department extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($department) {
            $department->slug = Str::slug($department->name);
        });
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
