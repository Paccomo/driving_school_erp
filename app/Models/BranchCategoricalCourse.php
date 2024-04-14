<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchCategoricalCourse extends Model
{
    protected $table = 'branch_categorical_course';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'fk_BRANCHid',
        'fk_CATEGORICAL_COURSEid'
    ];
}
