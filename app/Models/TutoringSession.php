<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoringSession extends Model
{
    protected $fillable = [
        'appointment_id',
        'student_performance',
        'topics_to_improve',
        'notes',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function materials()
    {
        return $this->hasMany(TutoringMaterial::class);
    }
}
