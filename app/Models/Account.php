<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Account extends Authenticatable
{
    protected $table = 'account';
    public $timestamps = false;
    
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function person()
    {
        return $this->hasOne(Person::class, 'id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id');
    }
}
