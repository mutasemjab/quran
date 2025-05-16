<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteStudent extends Model
{
    use HasFactory;
    protected $guarded=[];
    
      public function user()
    {
       return $this->belongsTo(User::class);
    }
      public function clas()
    {
       return $this->belongsTo(Clas::class);
    }
   
    public function lesson()
    {
       return $this->belongsTo(Lesson::class);
    }
}
