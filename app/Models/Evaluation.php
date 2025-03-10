<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'unit', 'Learning_outcomes', 'type', 'criteria', 'activity', 'first_partial', 'second_partial'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function cunit()
    {
        return $this->belongsTo(CurricularUnit::class, 'unit');
    }
}
