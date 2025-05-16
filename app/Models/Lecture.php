<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function classes()
    {
        return $this->belongsToMany(Clas::class, 'lecture_class_dates', 'lecture_id', 'class_id');
    }
    public function classDates()
    {
        return $this->hasMany(LectureClassDate::class, 'lecture_id');
    }
}
