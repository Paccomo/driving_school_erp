<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPresentationActivity extends Model
{
    protected $table = 'student_presentation_activity';
    public $timestamps = false;
    use HasFactory;
}
