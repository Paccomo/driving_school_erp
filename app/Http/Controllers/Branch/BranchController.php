<?php

namespace App\Http\Controllers\Branch;

use App\Constants\TimetableTimeType;
use App\Constants\WeekDay;
use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CategoricalCourse;
use App\Models\CompetenceCourse;
use App\Models\TimetableTime;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

//TODO branch index redirect to course registration
class BranchController extends Controller
{
    public function list() {
        $branches  =  Branch::all();
        foreach ($branches as $branch) {
            if ($branch->image === null) {
                $branch->image = url('storage/nophoto.webp');
            } else {
                $branch->image = url('storage/branchImages/'.$branch->image);
            }
        }
        return view('branch.branchList', ["branches" => $branches, 'roleDirector' => Role::Director->value]);
    }

    public function index(Request $request) {
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
            $branch->image = url('storage/branchImages/'.$branch->image);
        }

        return view('branch.branchIndex', ["branch" => $branch, "weekdays" => $weekdays, 'roleDirector' => Role::Director->value]);
    }

    public function edit(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');
        
    }

    public function add() {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $catCourses = CategoricalCourse::leftJoin('course', 'categorical_course.id', '=', 'course.id')->get();
        $compCourses = CompetenceCourse::leftJoin('course', 'competence_course.id', '=', 'course.id')->get();

        return view('branch.branchForm', [ "catCourses" => $catCourses, "compCourses" => $compCourses]);
    }

    public function destroy(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');
    }

    public function save(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

    }

    private function generateTimetable(int $branchID, array $days): array {
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

    private function getTimeValuesForDay(Collection $timetableValues): array {
        $timings = [];
        foreach ($timetableValues as $value) {
            switch ($value->time_type) {
                case TimetableTimeType::Open->value:
                    $timings[TimetableTimeType::Open->value] = $value->time;
                    break;
                case TimetableTimeType::Close->value:
                    $timings[TimetableTimeType::Close->value] = $value->time;
                    break;
                case TimetableTimeType::Break->value:
                    $timings[TimetableTimeType::Break->value] = $value->time;
                    break;
            }
        }
        return $timings;
    }
}
