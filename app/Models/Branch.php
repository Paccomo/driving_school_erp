<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branch';
    public $timestamps = false;
    // protected $fillable = [
    //     // Add other fillable attributes here if needed
    //     'monOpen',
    // ];
    use HasFactory;
}
