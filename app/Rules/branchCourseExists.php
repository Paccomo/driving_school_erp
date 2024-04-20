<?php

namespace App\Rules;

use App\Models\BranchCategoricalCourse;
use App\Models\BranchCompetenceCourse;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class branchCourseExists implements ValidationRule
{
    private $courseID;
    private $branchID;

    public function __construct($course, $branch)
    {
        $this->courseID = $course;
        $this->branchID = $branch;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $branchCat = BranchCategoricalCourse::where([
            ['fk_CATEGORICAL_COURSEid', $this->courseID],
            ['fk_BRANCHid', $this->branchID]
        ])->exists();
        $branchComp = BranchCompetenceCourse::where([
            ['fk_COMPETENCE_COURSEid', $this->courseID],
            ['fk_BRANCHid', $this->branchID]
        ])->exists();
        if (!$branchCat && !$branchComp) {
            $fail('Pasirinktame filiale norimas mokymas nėra ruošiamas');
        }
    }
}
