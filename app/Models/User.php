<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;



class User extends Authenticatable
{
   use HasApiTokens, HasFactory, Notifiable;

   /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $guarded = [];

   /**
    * The attributes that should be hidden for serialization.
    *
    * @var array<int, string>
    */
   protected $hidden = [
      'password',
      'remember_token',
   ];

   public function getBrothers(User $user)
   {
       return User::where('family_id', $user->family_id)->where('id', '!=', $user->id)->get();
   }



   public function grades()
    {
        return $this->hasMany(Grade::class);
    }


   public function classTeacher() // مربي صف
   {
      return $this->belongsTo(Clas::class);
   }

   public function clas()
   {
      return $this->belongsTo(Clas::class);
   }

   public function teacher()
   {
      return $this->hasOne(Teacher::class);
   }
  
   public function parentStudent()
   {
      return $this->hasOne(ParentStudent::class);
   }

       // User model
    public function teachersThatStudyStudent()
    {
        return $this->hasManyThrough(
            User::class,               // Target model (teachers in `users`)
            ClassTeacher::class,        // Intermediate table (class_teachers)
            'clas_id',                  // Foreign key on class_teachers (clas_id)
            'id',                       // Foreign key on User table (teachers' id)
            'clas_id',                  // Local key on User table (student's clas_id)
            'teacher_id'                // Local key on class_teachers table (teacher_id)
        )->where('users.user_type', 2); // Only teachers
    }




}
