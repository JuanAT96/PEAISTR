<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurricularUnit extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'unit', 'name', 'learning_outcomes'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teachingContents()
    {
        return $this->hasMany(TeachingContent::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'unit');
    }
}
