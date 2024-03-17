<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerOption extends Model
{
    protected $table = 'answer_option';
    public $timestamps = false;
    use HasFactory;
}
