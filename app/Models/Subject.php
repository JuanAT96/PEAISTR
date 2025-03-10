<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['subject_name', 'subject_code', 'curricular_org_unit', 'academic_period_id', 'credits', 'contact_teacher', 'practical_teaching', 'practical_self_employment', 'self_employment', 'description', 'objectives', 'gral_learning_outcome', 'description_integrative_project'];

    public function academicperiods()
    {
        return $this->belongsTo(AcademicPeriod::class, 'academic_period_id');
    }

    public function careers()
    {
        return $this->belongsToMany(Career::class, 'career_subjects');
    }

    public function requisites()
    {
        return $this->hasMany(SubjectRequisite::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'subject_teachers');
    }

    public function specificCompetences()
    {
        return $this->hasMany(SpecificCompetence::class);
    }

    public function genericCompetences()
    {
        return $this->hasMany(GenericCompetence::class);
    }

    public function teachingLearningStrategies()
    {
        return $this->hasMany(TeachingLearningStrategy::class);
    }

    public function curricularUnits()
    {
        return $this->hasMany(CurricularUnit::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function bibliographies()
    {
        return $this->hasMany(Bibliography::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function pea()
    {
        return $this->hasOne(Pea::class);
    }
}
