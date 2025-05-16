<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\ClassDateLesson;
use App\Models\ClassLesson;
use App\Models\ClassTeacher;
use App\Models\Lecture;
use App\Models\Clas;
use App\Models\LectureClassDate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClasController extends Controller
{
    
    public function getClassess()
    {
        $data = Clas::get();
         return response([
                'data' => $data,
            ], 200);
    }
    
  
    
     public function getNextLecture($classId)
    {
        $nextLecture = LectureClassDate::getNextLecture($classId);
        
        if ($nextLecture) {
            // Load the related lecture
            $lecture = $nextLecture->lecture;
            
            return response()->json([
                'success' => true,
                'next_lecture_date' => $nextLecture->formatted_date,
                'lecture' => $lecture
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No upcoming lectures found for this class'
        ]);
    }


    public function getAllLectures($classId)
    {
        $lectures = LectureClassDate::getAllLecturesForClass($classId);
        
        if ($lectures->count() > 0) {
            return response()->json([
                'success' => true,
                'lectures' => $lectures->map(function($lectureDate) {
                    return [
                        'lecture_date_id' => $lectureDate->id,
                        'date' => $lectureDate->formatted_date,
                        'lecture' => $lectureDate->lecture,
                        'is_past' => \Carbon\Carbon::parse($lectureDate->date)->isPast()
                    ];
                })
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No lectures found for this class'
        ]);
    }



    
}
