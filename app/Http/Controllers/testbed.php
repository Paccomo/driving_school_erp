<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class testbed extends Controller
{
    public function index() {
        $branches = Branch::all();
        $branchesField = $branches->first()->mon_open;
        return view('welcome');
    }
}
