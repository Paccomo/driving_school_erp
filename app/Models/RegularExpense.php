<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegularExpense extends Model
{
    protected $table = 'regular_expense';
    public $timestamps = false;
    use HasFactory;
}
