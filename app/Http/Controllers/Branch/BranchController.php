<?php

namespace App\Http\Controllers\Branch;

use App\Constants\TimetableTimeType;
use App\Constants\WeekDay;
use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchCategoricalCourse;
use App\Models\BranchCompetenceCourse;
use App\Models\CategoricalCourse;
use App\Models\CompetenceCourse;
use App\Models\TimetableTime;
use App\Rules\timeAfter;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

//TODO branch index redirect to course registration
class BranchController extends Controller
{
    public function list()
    {
        $branches = Branch::all();
        foreach ($branches as $branch) {
            if ($branch->image === null) {
                $branch->image = url('storage/nophoto.webp');
            } else {
                $branch->image = url('storage/branchImages/' . $branch->image);
            }
        }
        return view('branch.branchList', ["branches" => $branches, 'roleDirector' => Role::Director->value]);
    }

    public function index(Request $request)
    {
        $branch = Branch::find($request->id);

        $categoricalCourses = DB::table('branch_categorical_course')
            ->join('course', 'branch_categorical_course.fk_CATEGORICAL_COURSEid', '=', 'course.id')
            ->where('branch_categorical_course.fk_BRANCHid', $branch->id)
            ->select('course.name')
            ->get();
        $branch->categoricalCourses = $categoricalCourses;

        $competenceCourses = DB::table('branch_competence_course')
            ->join('course', 'branch_competence_course.fk_COMPETENCE_COURSEid', '=', 'course.id')
            ->where('branch_competence_course.fk_BRANCHid', $branch->id)
            ->select('course.name')
            ->get();
        $branch->competenceCourses = $competenceCourses;

        $weekdays = array_combine(array_column(WeekDay::cases(), 'value'), array_column(WeekDay::cases(), 'name'));

        $branch->timetable = $this->generateTimetable($request->id, $weekdays);

        if ($branch->image !== null) {
            $branch->image = url('storage/branchImages/' . $branch->image);
        }

        return view('branch.branchIndex', ["branch" => $branch, "weekdays" => $weekdays, 'roleDirector' => Role::Director->value]);
    }

    public function edit(Request $request)
    {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

    }

    public function add()
    {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $catCourses = CategoricalCourse::leftJoin('course', 'categorical_course.id', '=', 'course.id')->get();
        $compCourses = CompetenceCourse::leftJoin('course', 'competence_course.id', '=', 'course.id')->get();

        return view('branch.branchForm', ["catCourses" => $catCourses, "compCourses" => $compCourses]);
    }

    public function destroy(Request $request)
    {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');
    }

    public function save(Request $request)
    {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $request->validate([
            'id' => ['integer', 'gt:0', 'exists:branch,id'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'address' => ['required', 'regex:/^[\p{L}. \-\d]+$/u'],
            'city' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'phoneNum' => ['nullable', 'regex:/^(8|\+370)\s?\d(?:\s?\d){6,7}$/'],
            'image' => ['nullable', 'image'],
            'groupSize' => ['required', 'integer', 'gt:0'],
            'description' => ['nullable', 'string'],
            'courses' => ['array'],
            'courses.*' => ['integer', 'gt:0', 'exists:course,id']
        ]);
        foreach (array_column(WeekDay::cases(), 'value') as $weekday) {
            $request->validate([
                $weekday . "_" . TimetableTimeType::Open->value => ['nullable', 'date_format:H:i'],
                $weekday . "_" . TimetableTimeType::Break->value => ['nullable', 'date_format:H:i', new timeAfter($request, $weekday, 'Pertrauka')],
                $weekday . "_" . TimetableTimeType::Close->value => ['nullable', 'date_format:H:i', new timeAfter($request, $weekday, 'Uždarymo laikas'),
                    'after:' . $weekday . "_" . TimetableTimeType::Break->value]
            ]);
        }
        $rules = [];
        foreach ($request->courses as $courseId) {
            $rules["course{$courseId}_price"] = 'required_without:course' . $courseId . '_theory,course' . $courseId . '_practice,course' . $courseId . '_lesson|numeric|min:1';
            $rules["course{$courseId}_theory"] = 'required_without:course' . $courseId . '_price|numeric|min:1';
            $rules["course{$courseId}_practice"] = 'required_without:course' . $courseId . '_price|numeric|min:1';
            $rules["course{$courseId}_lesson"] = 'required_without:course' . $courseId . '_price|numeric|min:1';
        }
        $request->validate($rules);

        if ($request->has('id')) {
            $branch = Branch::find($request->id);
        } else {
            $branch = new Branch();
        }

        $branch->address = $request->address . ", " . $request->city;
        $branch->phone_number = $request->phoneNum;
        $branch->email = $request->email;
        $branch->description = $request->description;
        $branch->max_group_size = $request->groupSize;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();

            if (file_exists(storage_path('app/public/branchImages/' . $imageName))) {
                $extension = $image->getClientOriginalExtension();
                $imageName = uniqid() . '.' . $extension;
            }

            $image->storeAs('public/branchImages/', $imageName);
            $branch->image = $imageName;
        }

        $branch->save();
        $branchTimetable = TimetableTime::where('fk_BRANCHid', $branch->id)->get();
        $editedTimes = [];
        foreach (array_column(WeekDay::cases(), 'value') as $weekday) {
            foreach (array_column(TimetableTimeType::cases(), 'value') as $type) {
                if ($request->has($weekday . '_' . $type) && $request->get($weekday . '_' . $type) != null) {
                    $branchTime = $branchTimetable->first(function ($timetableValue) use ($weekday, $type) {
                        return $timetableValue->week_day == $weekday && $timetableValue->time_type == $type;
                    });

                    if ($branchTime !== null) {
                        $branchTime->time = $request->$weekday . '_' . $type;
                    } else {
                        $branchTime = new TimetableTime();
                        $branchTime->week_day = $weekday;
                        $branchTime->time_type = $type;
                        $branchTime->time = $request->get($weekday . '_' . $type);
                        $branchTime->fk_BRANCHid = $branch->id;
                    }
                    $branchTime->save();
                    $editedTimes[] = $branchTime;
                }
            }
        }
        $timesToDelete = $branchTimetable->diff($editedTimes);
        foreach($timesToDelete as $time) {
            $time->delete();
        }

        $CatCourses = CategoricalCourse::all();
        $CompCourses = CompetenceCourse::all();
        foreach ($request->get('courses') as $courseID) {
            $catCourse = $CatCourses->first(function ($course) use ($courseID) {
                return $course->id == $courseID;
            });
            $compCourse = $CompCourses->first(function ($course) use ($courseID) {
                return $course->id == $courseID;
            });

            if ($compCourse != null) {
                $branchCourse = BranchCompetenceCourse::firstOrCreate([
                    'fk_BRANCHid' => $branch->id,
                    'fk_COMPETENCE_COURSEid' => $courseID,
                ]);
                $branchCourse->price = 111;
                $branchCourse->save();
            } else if ($catCourse != null) {
                $branchCourse = BranchCategoricalCourse::firstOrCreate([
                    'fk_BRANCHid' => $branch->id,
                    'fk_CATEGORICAL_COURSEid' => $courseID,
                ]);
                $branchCourse->theoretical_course_price = 100;
                $branchCourse->practical_course_price = 200;
                $branchCourse->additional_lesson_price = 25;
                $branchCourse->save();
            }
        }
        BranchCategoricalCourse::whereNotIn('fk_CATEGORICAL_COURSEid', $request->get('courses'))
            ->where('fk_BRANCHid', $branch->id)
            ->delete();
        BranchCompetenceCourse::whereNotIn('fk_COMPETENCE_COURSEid', $request->get('courses'))
            ->where('fk_BRANCHid', $branch->id)
            ->delete();

        return Redirect::route('branch.list');
    }

    private function generateTimetable(int $branchID, array $days): array
    {
        $timetable = [];
        $timetableValues = TimetableTime::where('fk_BRANCHid', $branchID)->get();
        foreach ($days as $index => $day) {
            $timetableValuesForDay = $timetableValues->filter(function ($timetableValue) use ($index) {
                return $timetableValue->week_day == $index;
            });

            if ($timetableValuesForDay->isNotEmpty()) {
                $timetable[$index] = $this->getTimeValuesForDay($timetableValuesForDay);
            }
        }
        return $timetable;
    }

    private function getTimeValuesForDay(Collection $timetableValues): array
    {
        $timings = [];
        foreach ($timetableValues as $value) {
            switch ($value->time_type) {
                case TimetableTimeType::Open->value:
                    $timings[TimetableTimeType::Open->value] = $value->time;
                    break;
                case TimetableTimeType::Close->value:
                    $timings[TimetableTimeType::Close->value] = $value->time;
                    break;
                case TimetableTimeType::Break ->value:
                    $timings[TimetableTimeType::Break ->value] = $value->time;
                    break;
            }
        }
        return $timings;
    }
}
