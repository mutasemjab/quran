<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentStudent extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function children()
    {
        return $this->hasMany(ParentStudentReleation::class, 'parent_student_id');
    }

    public function parent()
    {
        return $this->belongsToMany(User::class, 'parent_student_relations', 'user_id', 'parent_student_id');
    }
}
