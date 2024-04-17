<?php

namespace App\Http\Controllers\Course;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\CategoricalCourse;
use App\Models\CompetenceCourse;
use App\Models\Description;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function list()
    {
        if (Auth::check()) {
            $courses = $this->getCourses(false);
        } else {
            $courses = $this->getCourses(true);
        }
        return view('course.courseList', [
            'categoricalCourses' => $courses['categorical'],
            'competenceCourses' => $courses['competence'],
            'roleDirector' => Role::Director->value
        ]);
    }

    public function index(Request $request) {
        if (CategoricalCourse::where('id', $request->id)->exists()) {
            $course = CategoricalCourse::with('course')->find($request->id);
            $course->additionToName = "Kategorija";
        } else if (CompetenceCourse::where('id', $request->id)->exists()) {
            $course = CompetenceCourse::with('course')->find($request->id);
        } else {
            return redirect()->route('course.list')->with('fail', 'Mokymo kursas nerastas!');
        }

        $highlightedDescriptions = $this->getDescriptions($request->id, true);
        $regularDescriptions = $this->getDescriptions($request->id, false);
        return view('course.courseIndex', [
            'course' => $course,
            'roleDirector' => Role::Director->value,
            'highlights' => $highlightedDescriptions,
            'info' => $regularDescriptions,
        ]);
    }

    public function register() {
        return null;
    }

    private function getCourses(bool $currentlyActive = false) : array {
        $categoricalCourses = CategoricalCourse::with('course');
        $competenceCourses = CompetenceCourse::with('course');
        if ($currentlyActive) {
            $categoricalCourses = $categoricalCourses->whereHas('branchCategoricalCourse');
            $competenceCourses = $competenceCourses->whereHas('branchCompetenceCourse');
        }
        $competenceCourses = $competenceCourses->get();
        $categoricalCourses = $categoricalCourses->get();
        return [
            'categorical' => $categoricalCourses,
            'competence' => $competenceCourses,
        ];
    }

    private function getDescriptions(int $fk, bool $distinguished = false) : Collection {
        $distinguished ? $distinguished = 1 : $distinguished = 0;
        return Description::where([
            'fk_COURSEid' => $fk,
            'is_distinguished' => $distinguished
        ])->get();
    }
}
