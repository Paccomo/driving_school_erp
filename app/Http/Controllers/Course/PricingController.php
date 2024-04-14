<?php

namespace App\Http\Controllers\Course;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchCategoricalCourse;
use App\Models\BranchCompetenceCourse;
use App\Models\CategoricalCourse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Redirect;

class PricingController extends Controller
{
    public function list() {
        $branches = Branch::all();
        foreach($branches as $branch) {
            $branch->catCourses = $this->getBranchCourses($branch->id, true);
            $branch->compCourses = $this->getBranchCourses($branch->id);
        }
        return view('course.pricingList', [
            'roleDirector' => Role::Director->value,
            'branches' => $branches
        ]);
    }

    public function edit(Request $request) {
        $validator = validator()->make([
            'course' => $request->courseid,
            'branch' => $request->branchid,
        ], [
            'course' => ['required', 'integer', 'gte:0', 'exists:course,id'],
            'branch' => ['required', 'integer', 'gte:0', 'exists:branch,id'],
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        if (CategoricalCourse::find($request->courseid) != null) {
            $branchCourse = BranchCategoricalCourse::where([
                'fk_BRANCHid' => $request->branchid,
                'fk_CATEGORICAL_COURSEid' => $request->courseid,
                ])
                ->first();

            $branch = Branch::find($request->branchid);
            
            if ($branchCourse != null) {
                return view('course.pricingCatForm', [
                    'branchCourse' => $branchCourse,
                    'branch' => $branch->name,
                ]);
            }
        } else {
            $branchCourse = BranchCompetenceCourse::where([
                'fk_BRANCHid' => $request->branchid,
                'fk_COMPETENCE_COURSEid' => $request->courseid,
                ])
                ->first();

            $branch = Branch::find($request->branchid);
            
            if ($branchCourse != null) {
                return view('course.pricingCompForm', [
                    'branchCourse' => $branchCourse,
                    'branch' => $branch->name,
                ]);
            } else {
                return Redirect::route('pricing.list')->with('fail', 'Norima redaguoti kurso kaina buvo nerasta. Įsitikinkite, ar tikrai norimame filiale tas kursas yra vykdomas.');
            }
        }
    }

    public function save(Request $request) {
        $request->validate([
            'id' => ['required', 'integer', 'gte:0'],
            'type' => 'required|in:categorical,competence',
        ]);
        if ($request->type === "competence") {
            $request->validate([
                'price' => ['required', 'numeric', 'gte:0']
            ]);

            $course = BranchCompetenceCourse::find($request->id);
            $course->price = $request->price;
        } else {
            $request->validate([
                'theoretical_course_price' => ['required', 'numeric', 'gte:0'],
                'practical_course_price' => ['required', 'numeric', 'gte:0'],
                'additional_lesson_price' => ['required', 'numeric', 'gte:0']
            ]);

            $course = BranchCategoricalCourse::find($request->id);
            $course->theoretical_course_price = $request->theoretical_course_price;
            $course->practical_course_price = $request->practical_course_price;
            $course->additional_lesson_price = $request->additional_lesson_price;
        }
        $course->save();
        return Redirect::route('pricing.list')->with('success', 'Kurso kaina sėkmingai atnaujinta.');
    }

    private function getBranchCourses(int $branchID, bool $categorical = false): Collection {
        if ($categorical)
            return $this->getCategoricalBranchCourses($branchID);
        return $this->getCompetenceBranchCourses($branchID);
    }

    private function getCategoricalBranchCourses(int $branchID): Collection {
        return BranchCategoricalCourse::select('branch_categorical_course.*', 'course.name')
            ->leftJoin('course', 'branch_categorical_course.fk_CATEGORICAL_COURSEid', '=', 'course.id')
            ->where('branch_categorical_course.fk_BRANCHid', $branchID)
            ->get();
    }

    private function getCompetenceBranchCourses(int $branchID): Collection {
        return BranchCompetenceCourse::select('branch_competence_course.*', 'course.name')
            ->leftJoin('course', 'branch_competence_course.fk_COMPETENCE_COURSEid', '=', 'course.id')
            ->where('branch_competence_course.fk_BRANCHid', $branchID)
            ->get();
    }
}
