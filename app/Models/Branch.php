<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branch';
    public $timestamps = false;

    public function timetableTime() {
        return $this->hasMany(TimetableTime::class, 'fk_BRANCHid');
    }

    public function categoricalCourse() {
        return $this->hasMany(BranchCategoricalCourse::class, 'fk_BRANCHid');
    }

    public function competenceCourse() {
        return $this->hasMany(BranchCompetenceCourse::class, 'fk_BRANCHid');
    }

    use HasFactory;
}
