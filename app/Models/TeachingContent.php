<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingContent extends Model
{
    use HasFactory;

    protected $fillable = ['curricular_units_id', 'schedule', 'content', 'contact_teacher', 'contact_teacher_duration', 'practical_teaching', 'practical_teaching_duration', 'practical_self_employment', 'practical_self_employment_duration', 'self_employment', 'self_employment_duration'];

    public function curricularUnit()
    {
        return $this->belongsTo(CurricularUnit::class, 'curricular_units_id');
    }
}
