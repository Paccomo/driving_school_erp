<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchCompetenceCourse extends Model
{
    protected $table = 'branch_competence_course';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'fk_BRANCHid',
        'fk_COMPETENCE_COURSEid'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'fk_BRANCHid');
    }
}
