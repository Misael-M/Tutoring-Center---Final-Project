<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'tutor_id',
        'day_of_week',
        'time_slot',
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
