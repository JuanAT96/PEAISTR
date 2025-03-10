<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pea extends Model
{
    use HasFactory;
    protected $table = 'pea';

    protected $fillable = ['subject_id', 'details'];
    protected $casts = [
        'details' => 'array',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
