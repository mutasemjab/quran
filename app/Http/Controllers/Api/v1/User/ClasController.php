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
    
    
   public function getNextLessonDate(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
        ]);
    
        $today = now()->toDateString();
    
        // Get the closest future date for the specified lesson
        $nextDate = LectureClassDate::where('lesson_id', $request->lesson_id)
            ->whereHas('classDate', function ($query) use ($today) {
                $query->where('week_date', '>=', $today);
            })
            ->with('classDate')
            ->join('class_dates', 'lecture_class_dates.class_date_id', '=', 'class_dates.id') // Ensure proper join
            ->orderBy('class_dates.week_date', 'asc') // Correct table alias
            ->select('lecture_class_dates.*') // Avoid ambiguous column references
            ->first();
    
        if (!$nextDate) {
            return response(['message' => 'No upcoming dates found for this lesson'], 404);
        }
    
        return response([
            'next_date' => $nextDate->classDate->week_date,
        ], 200);
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
