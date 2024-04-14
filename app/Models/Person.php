<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';
    public $timestamps = false;
    use HasFactory;

    public function getNameAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }

    public function getSurnameAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }

    public function employee() {
        return $this->hasOne(Employee::class, 'id');
    }
}
