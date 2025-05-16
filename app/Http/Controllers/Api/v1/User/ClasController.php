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


    public function getAllLessonDates(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
        ]);

        // Get all dates for the specified lecture
        $dates = LectureClassDate::where('lecture_id', $request->lecture_id)
            ->with('classDate')
            ->get()
            ->pluck('classDate.week_date');

        if ($dates->isEmpty()) {
            return response(['message' => 'No dates found for this lecture'], 404);
        }

        return response([
            'dates' => $dates,
        ], 200);
    }



    
}
