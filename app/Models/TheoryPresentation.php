<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheoryPresentation extends Model
{
    protected $table = 'theory_presentation';
    public $timestamps = false;
    use HasFactory;
    public $incrementing = true;
    
    protected $fillable = [
        'id',
        'order'
    ];

    public function link() {
        return $this->belongsTo(Link::class, 'id');
    }
}
