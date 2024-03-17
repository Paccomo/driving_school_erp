<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheoryPresentationTime extends Model
{
    protected $table = 'theory_presentation_time';
    public $timestamps = false;
    use HasFactory;
}
