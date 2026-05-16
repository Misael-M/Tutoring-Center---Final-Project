<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoringMaterial extends Model
{
    protected $fillable = [
        'tutoring_session_id',
        'file_path',
        'original_name',
    ];

    public function session()
    {
        return $this->belongsTo(TutoringSession::class, 'tutoring_session_id');
    }
}
