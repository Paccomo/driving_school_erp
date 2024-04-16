<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course';
    public $timestamps = false;
    use HasFactory;

    public function categoricalCourse()
    {
        return $this->hasOne(CategoricalCourse::class, 'id');
    }

    public function competenceCourse()
    {
        return $this->hasOne(CompetenceCourse::class, 'id');
    }
}
