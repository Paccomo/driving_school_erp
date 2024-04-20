<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoricalCourse extends Model
{
    protected $table = 'categorical_course';
    public $timestamps = false;
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(Course::class, 'id');
    }

    public function branchCategoricalCourse() {
        return $this->hasMany(BranchCategoricalCourse::class, 'fk_CATEGORICAL_COURSEid');
    }
}
