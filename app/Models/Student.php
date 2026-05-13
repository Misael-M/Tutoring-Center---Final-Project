<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'scholargrade_id',
        'school_name',
        'school_address',
        'topics_needed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scholarGrade()
    {
        return $this->belongsTo(ScholarGrade::class, 'scholargrade_id');
    }

    public function adultContacts()
    {
        return $this->hasMany(AdultContact::class);
    }

    public function studentImages()
    {
        return $this->hasMany(StudentImage::class);
    }
}
