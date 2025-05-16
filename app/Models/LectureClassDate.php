<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LectureClassDate extends Model
{
    protected $table = 'lecture_class_dates';
    public $timestamps = true;
    protected $fillable = [
        'lecture_id',
        'class_id',
        'date',
    ];
    
    public function lecture()
    {
        return $this->belongsTo(Lecture::class, 'lecture_id');
    }
    public function clas()
    {
        return $this->belongsTo(Clas::class, 'class_id');
    }
    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->date)->format('Y-m-d');
    }
}
