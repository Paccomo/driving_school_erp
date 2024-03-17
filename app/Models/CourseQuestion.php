<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseQuestion extends Model
{
    protected $table = 'course_question';
    public $timestamps = false;
    use HasFactory;
}
