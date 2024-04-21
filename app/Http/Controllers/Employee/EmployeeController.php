<?php

namespace App\Http\Controllers\Employee;

use App\Constants\Role;
use App\Constants\TimetableTimeType;
use App\Constants\WeekDay;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\DrivingLesson;
use App\Models\Employee;
use App\Models\InformationTemplate;
use App\Models\Person;
use App\Models\TimetableTime;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    public function __construct() {
        $this->middleware('role:director');
    }

    public function list() {
        $employees = Employee::with(['account', 'person'])
            ->orderBy('fk_BRANCHid')
            ->get();

        foreach ($employees as $employee) {
            $employee->fullName = $employee->person->name . " " . $employee->person->surname;
        }
        return view('employee.employeeList', ['employees' => $employees]);
    }

    public function index(Request $request) {
        $this->validateLocally($request->id);

        $employee = Employee::with(['account', 'person'])->find($request->id);
        $this->decypherPerson($employee);
        $employee->image = $this->getEmployeeImage($employee->image, true);

        $branch = Branch::find($employee->fk_BRANCHid);
        $timetable = $this->getTimetable($request->id);
        return view('employee.employeeIndex', ['employee' => $employee, 'timetable' => $timetable, 'branch' => $branch]);
    }

    public function edit(Request $request) {
        $this->validateLocally($request->id);

        $employee = Employee::with(['account', 'person'])->find($request->id);
        $this->decypherPerson($employee);
        $employee->image = $this->getEmployeeImage($employee->image);
        
        $fullAddress = explode(", ", $employee->person->address);
        $employee->person->address = $fullAddress[0];
        $employee->person->city = $fullAddress[1];

        $roles = array_combine(array_column(Role::cases(), 'value'), array_column(Role::cases(), 'name'));
        unset($roles[Role::Client->value]);
        unset($roles[Role::ExaminationAccount->value]);

        $branches = Branch::all();

        return view('employee.employeeForm', ['employee' => $employee, 'branches' => $branches, 'roles' => $roles]);
    }

    public function save(Request $request) {
        $request->validate([
            'id' => ['required', 'integer', 'gt:0', 'exists:employee,id'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => [Rule::enum(Role::class)],
            'name' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'surname' => ['required', 'regex:/^[\p{L} -]+$/u'],
            'pid' => ['required', 'integer', 'digits:11'],
            'address' => ['required', 'regex:/^[\p{L}. \-\d]+$/u'],
            'city' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'phoneNum' => ['required', 'regex:/^(8|\+370)\s?6\s?\d(?:\s?\d){6}$/'],
            'employmentTime' => ['required', 'numeric',  'gte:0.5', 'lte:1'],
            'image' => ['nullable', 'image'],
            'salary' => ['required', 'numeric', 'gte:0'],
            'branch' => ['required', 'integer',  'gt:0', 'exists:branch,id'],
        ]);

        $employee = Employee::with(['account', 'person'])->find($request->id);
        $employee->account->email = $request->email;
        $employee->account->role = $request->role;
        $employee->account->save();

        $employee->person->name = encrypt($request->name);
        $employee->person->surname = encrypt($request->surname);
        $employee->person->pid = encrypt($request->pid);
        $employee->person->address = encrypt($request->address . ", " . $request->city);
        $employee->person->phone_number = encrypt($request->phoneNum);
        $employee->person->save();

        $employee->fk_BRANCHid = $request->branch;
        $employee->monthly_salary = $request->salary;
        $employmentTime = $request->employmentTime;
        $employee->employment_time = $employmentTime;
        $employee->work_hours = $employmentTime * 40;
        $employee->save();
        return redirect()->route('employee.list')->with('success', 'Darbuotojo informacija atnaujinta');
    }

    public function destroy(Request $request) {
        $employee = Employee::find($request->id);
        if ($employee !== null) {
            TimetableTime::where('fk_EMPLOYEEid', $request->id)->delete();
            InformationTemplate::where('fk_EMPLOYEEid', $request->id)->delete();
            $lessons = DrivingLesson::where('fk_EMPLOYEEid', $request->id)->get();
            foreach ($lessons as $lesson) {
                $lesson->fk_EMPLOYEEid = null;
                $lesson->save();
            }
            $employee->delete();
            Person::destroy($request->id);
            Account::destroy($request->id);
            return redirect()->route('employee.list')->with('success', 'Darbuotojas sėkmingai ištrintas!');
        }
        return redirect()->route('employee.list')->with('fail', 'Norimas ištrinti darbuotojas buvo nerastas');
    }

    private function getEmployeeImage(?String $image, bool $getEmptyPhoto = false) {
        if ($image != null && file_exists(storage_path('app/public/employees/' . $image))) {
            $image = url('storage/employees/' . $image);
        } else if ($getEmptyPhoto) {
            $image = url('storage/nophoto.webp');
        } else 
            $image = null;
        return $image;
    }

    private function validateLocally($id) {
        $validator = validator()->make([
            'id' => $id,
        ], [
            'id' => ['required', 'integer', 'gte:0', 'exists:employee,id'],
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    private function decypherPerson(Employee &$person) {
        $person->person->pid = decrypt($person->person->pid);
        $person->person->address = decrypt($person->person->address);
        $person->person->phone_number = decrypt($person->person->phone_number);
    }

    private function getTimetable(int $employeeID): array
    {
        $timetable = [];
        $timetableValues = TimetableTime::where('fk_EMPLOYEEid', $employeeID)->get();
        foreach (array_column(WeekDay::cases(), 'value') as $day) {
            $timetableValuesForDay = $timetableValues->filter(function ($timetableValue) use ($day) {
                return $timetableValue->week_day == $day;
            });

            if ($timetableValuesForDay->isNotEmpty()) {
                $timetable[$day] = $this->getTimeValuesForDay($timetableValuesForDay);
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
