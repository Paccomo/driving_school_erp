<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientContractRequest extends Model
{
    protected $table = 'client_contract_request';
    public $timestamps = false;
    use HasFactory;

    public function client() {
        return $this->belongsTo(Client::class, "fk_CLIENTid");
    }
}
