<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalLink extends Model
{
    protected $table = 'external_link';
    public $timestamps = false;
    use HasFactory;
}
