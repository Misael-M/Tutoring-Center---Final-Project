<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarGrade extends Model
{
    protected $fillable = ['name'];

    //Relacion uno a muchos
    public function students(){
        return $this->hasMany(Student::class);
    }
}
