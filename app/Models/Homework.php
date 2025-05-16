<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function class()
    {
        return $this->belongsTo(Clas::class, 'clas_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function homeworkStudents()
    {
        return $this->hasMany(HomeworkStudent::class, 'homework_id');
    }

}
