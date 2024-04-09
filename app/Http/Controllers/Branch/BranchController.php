<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch;

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
        return view('branch.branchList', ["branches" => $branches]);
    }
}
