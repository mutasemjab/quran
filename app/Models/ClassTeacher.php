<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTeacher extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table = 'class_teachers';

    public function class()
    {
        return $this->belongsTo(Clas::class, 'clas_id');
    }
}
