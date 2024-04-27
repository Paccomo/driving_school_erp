<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';
    public $timestamps = false;
    use HasFactory;

    public function branch() {
        return $this->belongsTo(Branch::class, 'fk_BRANCHid');
    }

    public function course() {
        return $this->belongsTo(Course::class, 'fk_COURSEid');
    }

    public function contract() {
        return $this->hasMany(Contract::class, 'fk_CLIENTid');
    }

    public function document() {
        return $this->hasMany(Document::class, 'fk_CLIENTid');
    }
    
    public function account()
    {
        return $this->belongsTo(Account::class, 'id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'id');
    }
}
