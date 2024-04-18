<?php

namespace App\Http\Controllers\Course;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\BranchCategoricalCourse;
use App\Models\BranchCompetenceCourse;
use App\Models\CategoricalCourse;
use App\Models\Client;
use App\Models\CompetenceCourse;
use App\Models\Course;
use App\Models\CourseQuestion;
use App\Models\Description;
use App\Models\StudentsGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

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

    public function add() {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');
        return view('course.courseForm');
    }

    public function edit(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $validator = validator()->make([
            'id' => $request->id,
        ], [
            'id' => ['required', 'integer', 'gte:0', 'exists:course,id'],
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $course = Course::find($request->id);
        return view('course.courseForm', ['course' => $course]);
    }

    public function save(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $request->validate([
            'id' => ['integer', 'gt:0', 'exists:course,id'],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        unset($courseType);
        if ($request->has('id')) {
            $course = Course::find($request->id);
        } else {
            $course = new Course();
            $request->has('categorical') ? $courseType = new CategoricalCourse : $courseType = new CompetenceCourse;
        }

        $course->name = $request->name;
        $course->main_description = $request->description;
        $course->save();
        if (isset($courseType) && $courseType !== null)  {
            $courseType->id = $course->id;
            $courseType->save();
        }

        return redirect()->route('course.list')->with('success', 'Duomenys sėkmingai išsaugoti');
    }

    public function destroy(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $course = Course::find($request->id);
        if ($course !== null) {
            Description::where('fk_COURSEid', $request->id)->delete();
            CourseQuestion::where('fk_COURSEid', $request->id)->delete();
            BranchCategoricalCourse::where('fk_CATEGORICAL_COURSEid', $request->id)->delete();
            BranchCompetenceCourse::where('fk_COMPETENCE_COURSEid', $request->id)->delete();
            CategoricalCourse::where('id', $request->id)->delete();
            CompetenceCourse::where('id', $request->id)->delete();
            $studentsGroups = StudentsGroup::where('fk_COURSEid', $request->id)->get();
            foreach ($studentsGroups as $group) {
                $group->fk_COURSEid = null;
                $group->save();
            }
            $clients = Client::where('fk_COURSEid', $request->id)->get();
            foreach ($clients as $client) {
                $client->fk_COURSEid = null;
                $client->save();
            }
            $course->delete();
            return redirect()->route('course.list')->with('success', 'Mokymo kursas sėkmingai ištrintas!');
        }
        return redirect()->route('course.list')->with('fail', 'Norimas ištrinti mokymo kursas buvo nerastas');
    }

    public function register() {
        return null;
    }

    public function descList(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $validator = validator()->make([
            'id' => $request->id,
        ], [
            'id' => ['required', 'integer', 'gte:0', 'exists:course,id'],
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $courseName = Course::where('id', $request->id)->value('name');
        $distinguished = $this->getDescriptions($request->id, true);
        $regular = $this->getDescriptions($request->id, false);
        return view('course.descList', ['distinguished' => $distinguished, 'regular' => $regular, 'courseName' => $courseName]);
    }

    public function descIndex(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $validator = validator()->make([
            'id' => $request->id,
        ], [
            'id' => ['required', 'integer', 'gte:0', 'exists:description,id'],
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $description = Description::find($request->id);
        return view('course.descIndex', ['description' => $description]);
    }

    public function descAdd() {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $courses = Course::all();

        return view('course.descForm', ['courses' => $courses]);
    }

    public function descSave(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $request->validate([
            'id' => ['integer', 'gt:0', 'exists:description,id'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'course' => ['required', 'integer', 'gt:0', 'exists:course,id']
        ]);

        if ($request->has('id'))
            $desc = Description::find($request->id);
        else 
            $desc = new Description();

        $desc->title = $request->title;
        $desc->description = $request->description;
        $desc->fk_COURSEid = $request->course;
        if ($request->has('distinguished'))
            $desc->is_distinguished = 1;
        else
            $desc->is_distinguished = 0;
        $desc->save();

        return redirect()->route('description.list', [$request->course])->with('success', 'Duomenys sėkmingai išsaugoti');
    }

    public function descEdit(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $validator = validator()->make([
            'id' => $request->id,
        ], [
            'id' => ['required', 'integer', 'gte:0', 'exists:description,id'],
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $description = Description::find($request->id);
        return view('course.descForm', ['description' => $description]);
    }

    public function descDestroy(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $desc = Description::find($request->id);
        if ($desc !== null) {
            $fk = $desc->fk_COURSEid;
            $desc->delete();
            return redirect()->route('description.list', [$fk])->with('success', 'Mokymo kurso aprašymas sėkmingai ištrintas!');
        }
        return redirect()->route('course.list')->with('fail', 'Norimas ištrinti mokymo kurso aprašymas buvo nerastas');
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
