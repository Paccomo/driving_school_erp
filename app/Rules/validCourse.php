<?php

namespace App\Rules;

use App\Models\BranchCategoricalCourse;
use App\Models\BranchCompetenceCourse;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class validCourse implements ValidationRule
{

    protected $branchId;

    public function __construct($branchId)
    {
        $this->branchId = $branchId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $catCourses = BranchCategoricalCourse::where([
            ['fk_CATEGORICAL_COURSEid', $value],
            ['fk_BRANCHid', $this->branchId]
        ])->count();
        $comCourses = BranchCompetenceCourse::where([
            ['fk_COMPETENCE_COURSEid', $value],
            ['fk_BRANCHid', $this->branchId]
        ])->count();
        if ($comCourses+$catCourses < 1) {
            $fail('Kursas parinktame filiale nėra vykdomas');
        }
    }
}
