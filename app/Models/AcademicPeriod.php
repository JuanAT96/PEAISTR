<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'estado'];

    public function subject()
    {
        return $this->hasMany(Subject::class);
    }
}
