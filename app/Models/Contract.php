<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contract';
    public $timestamps = false;
    use HasFactory;

    public function contractRequest() {
        return $this->belongsTo(ContractRequest::class, 'fk_CONTRACTid');
    }
}
