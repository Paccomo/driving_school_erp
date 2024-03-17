<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetenceCourse extends Model
{
    protected $table = 'competence_course';
    public $timestamps = false;
    use HasFactory;
}
