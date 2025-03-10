<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectRequisite extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'prerequisites', 'corequisites'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function prerequisiteSubject()
    {
        return $this->belongsTo(Subject::class, 'prerequisites');
    }

    public function corequisiteSubject()
    {
        return $this->belongsTo(Subject::class, 'corequisites');
    }
}
