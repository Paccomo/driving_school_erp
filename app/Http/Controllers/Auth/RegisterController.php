<?php

namespace App\Http\Controllers\Auth;

use App\Constants\TimetableTimeType;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchCategoricalCourse;
use App\Models\BranchCompetenceCourse;
use App\Models\CategoricalCourse;
use App\Models\Client;
use App\Models\CompetenceCourse;
use App\Models\Employee;
use App\Models\Person;
use App\Constants\Role;
use App\Models\StudentsGroup;
use App\Models\TimetableTime;
use App\Rules\validCourse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __construct() {
        $this->middleware('role:administrator,director');
    }

    public function register(Request $request) {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => [Rule::enum(Role::class)->when(Auth::user()->role == "administrator", fn ($rule) => $rule->only([Role::Client]))],
            'name' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'surname' => ['required', 'regex:/^[\p{L} -]+$/u'],
            'pid' => ['required', 'integer', 'digits:11'],
            'address' => ['required', 'regex:/^[\p{L}. \-\d]+$/u'],
            'city' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'phoneNum' => ['required', 'regex:/^(8|\+370)\s?6\s?\d(?:\s?\d){6}$/'],
        ]);

        if (Auth::user()->role == Role::Director->value) {
            $request->validate([
                'branch' => ['required', 'integer',  'gt:0', 'exists:branch,id'],
            ]);
            $branch = $request->branch;
        } else {
            $branch = Employee::find(Auth::user()->id)->fk_BRANCHid;
        }

        if ($request->role == Role::Client->value) {
            $request->validate([
                'course' => ['required', 'integer',  'gte:0', 'exists:course,id',  new validCourse($branch)],
                'prepaid' => ['required', 'numeric',  'gte:0'],
                'group' => ['nullable', 'integer',  'gte:0', 'exists:students_group,id'],
            ]);

            $createConcreteUser = function (Request $request, int $userID, int $branch) {
                $this->createClient($userID, $request['prepaid'], $branch, $request['course'], $request['noTheory'] !== null, $request['group'], $request['extension'] !== null);
            };
        } else {
            $request->validate([
                'employmentTime' => ['required', 'numeric',  'gte:0.5', 'lte:1'],
                'image' => ['nullable', 'image'],
                'salary' => ['required', 'numeric', 'gte:0'],
            ]);

            $createConcreteUser = function (Request $request, int $userID, int $branch) {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                } else {
                    $image = null;
                }
                $this->createEmployee($userID, $branch, $request['salary'], $request['employmentTime'], $image);
            };
        }

        $password = Str::password(8, true, true, false, false);
        $account = Account::create([
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
        ]);

        $person = new Person();
        $person->id = $account->id;
        $person->name = encrypt($request->name);
        $person->surname = encrypt($request->surname);
        $person->pid = encrypt($request->pid);
        $person->address = encrypt($request->address . ", " . $request->city);
        $person->phone_number = encrypt($request->phoneNum);
        $person->save();

        $createConcreteUser($request, $account->id, $branch);

        return view('auth.display', [
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'pw' => $password
        ]);
    }

    public function showRegistrationForm(Request $request) {
        $type = $request->type;
        if ($type !== null && $type !== 'employee')
            return Redirect::route('register');
        elseif ($type !== null && Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $branches = Branch::all();
        if (Auth::user()->role == Role::Director->value) {
            $categoryCourses = BranchCategoricalCourse::leftJoin('course', 'branch_categorical_course.fk_CATEGORICAL_COURSEid', '=', 'course.id')->get();
            $compCourses = BranchCompetenceCourse::leftJoin('course', 'branch_competence_course.fk_COMPETENCE_COURSEid', '=', 'course.id')->get();
        }
        else {
            $categoryCourses = BranchCategoricalCourse::leftJoin('course', 'branch_categorical_course.fk_CATEGORICAL_COURSEid', '=', 'course.id')
            ->where('fk_BRANCHid', Employee::find(Auth::user()->id)->fk_BRANCHid)->get();
            $compCourses = BranchCompetenceCourse::leftJoin('course', 'branch_competence_course.fk_COMPETENCE_COURSEid', '=', 'course.id')
            ->where('fk_BRANCHid', Employee::find(Auth::user()->id)->fk_BRANCHid)->get();
        }
        if (Auth::user()->role == Role::Director->value) {
            $groups = StudentsGroup::select('students_group.*', "course.name")
            ->leftJoin('course', 'students_group.fk_COURSEid', '=', 'course.id')
            ->where([
                ['date_start', '>', Carbon::now()]
            ])->get();
        }
        else {
            $groups = StudentsGroup::select('students_group.*', "course.name")
            ->leftJoin('course', 'students_group.fk_COURSEid', '=', 'course.id')
            ->where([
                ['fk_BRANCHid', Employee::find(Auth::user()->id)->fk_BRANCHid],
                ['date_start', '>', Carbon::now()]
            ])->get();
        }
        $groups = $this->removeFullGroups($groups);

        $roles = array_combine(array_column(Role::cases(), 'value'), array_column(Role::cases(), 'name'));
        unset($roles[Role::Client->value]);
        unset($roles[Role::ExaminationAccount->value]);

        return view('auth.register', [
            'roles' => $roles,
            'roleDirector' => Role::Director->value,
            'employeeForm' => $type !== null,
            'branches' =>  $branches,
            'catCourses' => $categoryCourses,
            'comCourses' => $compCourses,
            'groups' => $groups
        ]);
    }

    public function UserPdf(Request $request) {
        $pdf = Pdf::loadView('auth/displayPdf', [
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'pw' => $request->pw,
        ]);
        return $pdf->download("credentials.pdf");
    }

    private function createClient(int $userID, float $prepaid, int $branchID, int $courseID, bool $withoutTheory = false, int $chosenGroupID = null, bool $extension): void {
        $client = new Client();
        $client->id = $userID;
        $client->practical_lessons_permission = $extension;
        $client->currently_studying = true;
        $client->to_pay = $this->calculateCoursePrice($branchID, $courseID, $withoutTheory, $extension) - $prepaid;
        $client->fk_COURSEid = $courseID;
        $client->fk_BRANCHid = $branchID;
        if (!$withoutTheory)
            $client->fk_STUDENTS_GROUPid = $chosenGroupID !== null ? $chosenGroupID : $this->getViableStudentsGroup($branchID, $courseID);
        $client->save();
    }

    private function createEmployee(int $userID, int $branchID, float $salary, float $employmentTime, $image = null) : void {
        $employee = new Employee();
        $employee->id = $userID;
        $employee->fk_BRANCHid = $branchID;
        $employee->monthly_salary = $salary;
        $employee->employment_time = $employmentTime;
        $employee->work_hours = $employmentTime * 40;
        if ($image != null) {
            $imageName = $image->getClientOriginalName();

            if (file_exists(storage_path('app/public/employees/' . $imageName))) {
                $extension = $image->getClientOriginalExtension();
                $imageName = uniqid() . '.' . $extension;
            }

            $image->storeAs('public/employees/', $imageName);
            $employee->image = $imageName;
        }
        $employee->save();
    }

    private function calculateCoursePrice(int $branchID, int $courseID, bool $withoutTheory, bool $extensionContract): float {
        if ($extensionContract)
            return 0;
        
        if (CategoricalCourse::find($courseID) !== null) {
            $courseClass = BranchCategoricalCourse::class;
            $courseColumn = "fk_CATEGORICAL_COURSEid";
        }
        else if (CompetenceCourse::find($courseID) !== null) {
            $courseClass = BranchCompetenceCourse::class;
            $courseColumn = "fk_COMPETENCE_COURSEid";
        }
        else
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Chosen course does not exist.');

        $branchCourse = $courseClass::where([
                [$courseColumn, $courseID],
                ["fk_BRANCHid", $branchID]
        ])->first();

        if ($branchCourse instanceof BranchCompetenceCourse)
            return $branchCourse->price;
        else 
            return $withoutTheory ? $branchCourse->practical_course_price : ($branchCourse->practical_course_price + $branchCourse->theoretical_course_price);
    }

    private function getViableStudentsGroup(int $branchID, int $courseID): int {
        $group = StudentsGroup::where([
            ['date_start', '>', Carbon::tomorrow()->startOfDay()],
            ['fk_BRANCHid', $branchID],
            ['fk_COURSEid', $courseID],
        ])->orderBy('date_start', 'asc')
        ->get();
        $group = $this->removeFullGroups($group);
        $group = $group->first();

        if ($group === null) {
            $group = new StudentsGroup();
            $group->fk_COURSEid = $courseID;
            $group->fk_BRANCHid = $branchID;
            $group->date_start = $this->calculateGroupStartingDate($branchID);
            $group->save();
        }

        return $group->id;
    }

    private function calculateGroupStartingDate(int $branchID): Carbon {
        $startDate = Carbon::now()->addDays(14);
        for ($i = 0; $i < 7; $i++) {
            $weekDayTimetable = TimetableTime::where([
                ['fk_BRANCHid', $branchID],
                ['week_day', strtolower($startDate->format('l'))],
            ])->get();

            if ($weekDayTimetable !== null) {
                foreach ($weekDayTimetable as $dayTimeTable) {
                    if ($dayTimeTable->time_type == TimetableTimeType::Open->value) {
                        $time = Carbon::parse($dayTimeTable->time);
                        $startDate->setTime($time->hour, $time->minute, $time->second);
                        return $startDate;
                    }
                }
                return $startDate->startOfDay();
            }

            $startDate = $startDate->addDays(1);
        }
        return $startDate = $startDate->subDays(7)->startOfDay();
    }

    private function removeFullGroups(Collection $groups): Collection {
        $groups = $groups->reject(function ($gr, $idx) {
            $maxStudentsInGroup = Branch::find($gr->fk_BRANCHid)->max_group_size;
            $studentsInGroup = Client::where("fk_STUDENTS_GROUPid", $gr->id)->count();
            return $studentsInGroup >= $maxStudentsInGroup;
        });
        return $groups;
    }
}
