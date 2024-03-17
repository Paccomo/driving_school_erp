<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    protected $table = 'description';
    public $timestamps = false;
    use HasFactory;
}
