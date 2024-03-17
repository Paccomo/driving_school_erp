<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTimetable extends Model
{
    protected $table = 'employee_timetable';
    public $timestamps = false;
    use HasFactory;
}
