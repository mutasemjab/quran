<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkAnswer extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function homework()
    {
        return $this->belongsTo(Homework::class, 'homework_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
