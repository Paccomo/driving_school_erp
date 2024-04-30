<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrivingLesson extends Model
{
    protected $table = 'driving_lesson';
    public $timestamps = false;
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class, 'fk_CLIENTid');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'fk_EMPLOYEEid');
    }
}
