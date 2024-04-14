<?php

namespace App\Http\Controllers\Employee;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function list()
    {
        $employees = Employee::with(['account', 'person'])
        ->whereHas('account', function ($query) {
            $query->where('role', Role::Instructor->value);
        })
        ->orderBy('fk_BRANCHid')
        ->get();
        foreach ($employees as $employee) {
            $employee->image = $this->getImage($employee->image);
            $employee->branchAddress = Branch::find($employee->fk_BRANCHid)->address;
            $employee->phoneNum = decrypt($employee->person->phone_number);
        }
        return view('employee.instructorList', ["employees" => $employees, 'roleDirector' => Role::Director->value]);
    }

    private function getImage($imageName): string {
        if ($imageName != null && file_exists(storage_path('app/public/employees/' . $imageName))) {
            return url('storage/employees/' . $imageName);
        }
        return url('storage/nophoto.webp');
    }
}
