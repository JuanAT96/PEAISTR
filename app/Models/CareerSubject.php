<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerSubject extends Model
{
    use HasFactory;

    protected $fillable = ['career_id', 'subject_id', 'level'];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
