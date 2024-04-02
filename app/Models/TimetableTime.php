<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableTime extends Model
{
    protected $table = 'timetable_time';
    public $timestamps = false;
    use HasFactory;
}
