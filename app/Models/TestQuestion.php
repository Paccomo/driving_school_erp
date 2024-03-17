<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    protected $table = 'test_question';
    public $timestamps = false;
    use HasFactory;
}
