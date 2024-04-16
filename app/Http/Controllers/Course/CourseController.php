<?php

namespace App\Http\Controllers\Course;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\CategoricalCourse;
use App\Models\CompetenceCourse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function list()
    {
        $categoricalCourses = CategoricalCourse::with('course')
            ->whereHas('branchCategoricalCourse')
            ->get();
        $competenceCourses = CompetenceCourse::with('course')
            ->whereHas('branchCompetenceCourse')
            ->get();
        return view('course.courseList', [
            'categoricalCourses' => $categoricalCourses,
            'competenceCourses' => $competenceCourses,
            'roleDirector' => Role::Director->value
        ]);
    }

    public function index() {
        $course = CategoricalCourse::with('course')->find(5);
        $course->additionToName = "Kategorija";
        return view('course.courseIndex', ['course' => $course, 'roleDirector' => Role::Director->value]);
    }

    public function register() {
        return null;
    }
}
