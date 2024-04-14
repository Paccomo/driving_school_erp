<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee';
    public $timestamps = false;
    use HasFactory;

    public function account()
    {
        return $this->belongsTo(Account::class, 'id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'id');
    }
}
