<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassDate extends Model
{
    use HasFactory;

    protected $fillable = ['clas_id', 'week_date'];

    public function class()
    {
        return $this->belongsTo(Clas::class, 'clas_id');
    }

    public function lectures()
    {
        return $this->hasMany(LectureClassDate::class, 'class_date_id');
    }
}
