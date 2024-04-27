<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractRequest extends Model
{
    protected $table = 'contract_request';
    public $timestamps = false;
    use HasFactory;

    public function guestReq() {
        return $this->hasOne(GuestContractRequest::class, 'id');
    }

    public function clientReq() {
        return $this->hasOne(ClientContractRequest::class, 'id');
    }

    public function contract() {
        return $this->hasOne(Contract::class, 'fk_CONTRACT_REQUESTid');
    }
}
