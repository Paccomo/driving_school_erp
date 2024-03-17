<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestContractRequest extends Model
{
    protected $table = 'guest_contract_request';
    public $timestamps = false;
    use HasFactory;
}
