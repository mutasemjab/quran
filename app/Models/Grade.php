<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
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
   
    public function lecture()
    {
       return $this->belongsTo(Lecture::class);
    }
}
