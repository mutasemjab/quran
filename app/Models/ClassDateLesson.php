<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassDateLesson extends Model
{
    use HasFactory;

    protected $fillable = ['class_date_id', 'lesson_id'];

    /**
     * Relationship with ClassDate
     * A lesson is associated with a specific weekly date.
     */
    public function classDate()
    {
        return $this->belongsTo(ClassDate::class, 'class_date_id');
    }

    /**
     * Relationship with Lesson
     * A lesson is associated with a weekly date.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
    
}
