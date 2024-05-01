<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class instructorReservedTime extends Model
{
    protected $table = 'instructor_reserved_time';
    public $timestamps = false;
    use HasFactory;
}
