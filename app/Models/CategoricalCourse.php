<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoricalCourse extends Model
{
    protected $table = 'categorical_course';
    public $timestamps = false;
    use HasFactory;
}
