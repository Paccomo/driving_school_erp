<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractRequest extends Model
{
    protected $table = 'contract_request';
    public $timestamps = false;
    use HasFactory;
}
