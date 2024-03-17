<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsGroup extends Model
{
    protected $table = 'students_group';
    public $timestamps = false;
    use HasFactory;
}
