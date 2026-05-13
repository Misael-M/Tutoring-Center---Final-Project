<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdultContact extends Model
{
    protected $fillable = [
        'student_id',
        'name',
        'phone',
        'relationship',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
