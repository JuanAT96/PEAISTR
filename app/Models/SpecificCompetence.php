<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificCompetence extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'graduate_profile', 'Learning_outcomes', 'contribution_level'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
