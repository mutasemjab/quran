<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function classDateLessons()
    {
        return $this->hasMany(ClassDateLesson::class, 'lesson_id');
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'lesson_id');
    }
}
