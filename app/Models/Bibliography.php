<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bibliography extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'type', 'author', 'year', 'title', 'editorial', 'format', 'code', 'link', 'uplift'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
