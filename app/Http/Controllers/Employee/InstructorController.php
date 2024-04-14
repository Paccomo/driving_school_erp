<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function list()
    {
        $employees = Employee::with('account')->get();
        // foreach ($branches as $branch) {
        //     $branch->image = $this->getImage($branch->image);
        // }
        // return view('branch.branchList', ["branches" => $branches, 'roleDirector' => Role::Director->value]);
    }

    private function getImage($imageName): string {
        if ($imageName != null && file_exists(storage_path('app/public/employees/' . $imageName))) {
            return url('storage/employees/' . $imageName);
        }
        return url('storage/nophoto.webp');
    }
}
