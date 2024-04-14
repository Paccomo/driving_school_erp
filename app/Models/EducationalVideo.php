<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalVideo extends Model
{
    protected $table = 'educational_video';
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'id'
    ];

    public function link() {
        return $this->belongsTo(Link::class, 'id');
    }
}
