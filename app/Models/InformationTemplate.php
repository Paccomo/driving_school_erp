<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformationTemplate extends Model
{
    protected $table = 'information_template';
    public $timestamps = false;
    use HasFactory;
}
