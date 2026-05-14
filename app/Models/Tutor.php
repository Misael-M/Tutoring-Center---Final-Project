<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $fillable = [
        'user_id',
        'specialty',
        'license_number',
    ];

    // Relación uno a uno inversa con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación uno a muchos con Appointment
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Relación uno a muchos con Schedule
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
