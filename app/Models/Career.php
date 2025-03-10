<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'research_lines', 'coordinator', 'modality', 'duration', 'logo_path'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'career_subjects');
    }
}
