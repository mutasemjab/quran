<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Athkar;

use App\Models\Exam;
use App\Models\Game;
use App\Models\Homework;
use App\Models\HomeworkAnswer;
use App\Models\Interaction;
use App\Models\LectureClassDate;
use App\Models\Seera;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function storeInteraction(Request $request)
    {
        $userId = auth()->user()->id;
        // Validate the input
        $request->validate([
            'type_of_interaction' => 'required|in:1,2,3,4,5,6', // 1: Pray, 2: athkar_after_pray, 3: athkar, 4: asmaa_allah_alhosna 5: games 6: taqweya
            'points' => 'required|integer|min:1',
            'clas_id' => 'required|exists:clas,id',
        ]);

        // Find an existing interaction for the same user and interaction type
        $interaction = Interaction::where('type_of_interaction', $request->type_of_interaction)
            ->where('user_id', $userId)
            ->where('clas_id', $request->clas_id)
            ->first();

        if ($interaction) {
            // Increment points if interaction exists
            $interaction->points += $request->points;
            $interaction->save();
        } else {
            // Create a new interaction if it doesn't exist
            Interaction::create([
                'type_of_interaction' => $request->type_of_interaction,
                'points' => $request->points,
                'user_id' => $userId,
                'clas_id' => $request->clas_id,
            ]);
        }

        return response(['message' => 'Interaction stored successfully'], 200);
    }

    public function getAthkar()
    {
        $data = Athkar::get();

        return response()->json(['data' => $data]);
    }
    public function getSeera()
    {
        $data = Seera::get();

        return response()->json(['data' => $data]);
    }

   public function getClassLectures($clas_id)
    {
        // Retrieve lecture class dates for the given class ID
        $lectureDates = LectureClassDate::where('class_id', $clas_id)
            ->with('lecture')  // Eager load the lecture relationship
            ->orderBy('date', 'asc')
            ->get();

        if ($lectureDates->isEmpty()) {
            return response()->json(['message' => 'No lectures found for this class.'], 404);
        }

        $data = [];
        $currentDate = null;
        $dateGroup = [];

        // Group lectures by date
        foreach ($lectureDates as $lectureDate) {
            $formattedDate = $lectureDate->formatted_date;
            
            // If we encounter a new date, create a new group
            if ($currentDate !== $formattedDate) {
                // Add the previous group to our data array if it's not empty
                if (!empty($dateGroup)) {
                    $data[] = [
                        'date' => $currentDate,
                        'lectures' => $dateGroup,
                        'is_past' => \Carbon\Carbon::parse($currentDate)->isPast(),
                        'is_today' => \Carbon\Carbon::parse($currentDate)->isToday()
                    ];
                }
                
                // Start a new group
                $currentDate = $formattedDate;
                $dateGroup = [];
            }
            
            // Add the lecture to the current date group
            $dateGroup[] = [
                'lecture_date_id' => $lectureDate->id,
                'lecture_id' => $lectureDate->lecture->id,
                'type' => $lectureDate->lecture->type, // 1: Quran, 2: Hadeth, 3: Manhag
                'content_student' => $lectureDate->lecture->content_student,
                'content_teacher' => $lectureDate->lecture->content_teacher,
                'video' => $lectureDate->lecture->video,
            ];
        }
        
        // Add the last group if it exists
        if (!empty($dateGroup)) {
            $data[] = [
                'date' => $currentDate,
                'lectures' => $dateGroup,
                'is_past' => \Carbon\Carbon::parse($currentDate)->isPast(),
                'is_today' => \Carbon\Carbon::parse($currentDate)->isToday()
            ];
        }

        return response()->json($data, 200);
    }


    public function getHomeworks(Request $request)
    {
        // Validate the input
        $request->validate([
            'clas_id' => 'required|exists:clas,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:all,special', // 'all' for all users, 'special' for specific users
        ]);

        // Check if the child is assigned to the class
        $classAssigned = User::where('id', $request->user_id)
            ->where('clas_id', $request->clas_id)
            ->exists();

        if (!$classAssigned) {
            return response(['message' => 'This student is not assigned to this class'], 403);
        }

        // Query homeworks for the given class
        $query = Homework::where('clas_id', $request->clas_id)
            ->with('lecture') // Assuming there's a lecture relationship
            ->with(['homeworkStudents' => function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            }]);

        // If "special", filter only the homeworks assigned specifically to this user
        if ($request->type === 'special') {
            $query->whereHas('homework_students', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        $homeworks = $query->get();

        if ($homeworks->isEmpty()) {
            return response(['message' => 'No homework found for this student'], 404);
        }

        // Format response
        $data = $homeworks->map(function ($homework) {
            return [
                'homework_id' => $homework->id,
                'type' => $homework->type == 1 ? 'Quran' : ($homework->type == 2 ? 'Manhg' : 'Extra'),
                'name_of_sura' => $homework->name_of_sura,
                'from' => $homework->from,
                'to' => $homework->to,
                'description' => $homework->type == 2 ? $homework->description_manhag : ($homework->type == 3 ? $homework->description_extra : null),
                'status' => $homework->status == 1 ? 'Done' : 'Not Yet',
                'lecture_id' => $homework->lecture_id,
                'teacher_id' => $homework->teacher_id,
                'assigned_to' => $homework->homeworkStudents->pluck('user_id')->toArray(),
            ];
        });

        return response(['homeworks' => $data], 200);
    }

    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'homework_id' => 'required|exists:homework,id',
            'user_id' => 'required|exists:users,id',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg|max:20480', // Max 20MB
            'voice' => 'nullable|file|mimes:mp3,wav|max:20480', // Max 20MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $answers = [];

        // Save photo if exists
        if ($request->hasFile('photo')) {
            $filePath = uploadImage('assets/admin/uploads', $request->file('photo'));
            $answers[] = HomeworkAnswer::create([
                'homework_id' => $request->homework_id,
                'user_id' => $request->user_id,
                'answer_type' => 'photo',
                'file_path' => $filePath,
            ]);
        }

        // Save voice if exists
        if ($request->hasFile('voice')) {
            $filePath = uploadImage('assets/admin/uploads', $request->file('voice'));
            $answers[] = HomeworkAnswer::create([
                'homework_id' => $request->homework_id,
                'user_id' => $request->user_id,
                'answer_type' => 'voice',
                'file_path' => $filePath,
            ]);
        }

        return response()->json([
            'message' => 'Homework answer(s) submitted successfully!',
            'data' => $answers
        ], 200);
    }

    public function getAnswers(Request $request)
    {
        $request->validate([
            'homework_id' => 'required|exists:homework,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $answers = HomeworkAnswer::where('homework_id', $request->homework_id)
            ->where('user_id', $request->user_id)
            ->get();

        return response()->json(['answers' => $answers]);
    }


    public function getInteractions(Request $request)
    {
        $userId = auth()->user()->id;
        // Validate the input
        $request->validate([
            'clas_id' => 'required|exists:clas,id',
        ]);

        $data = Interaction::where('user_id', $userId)
        ->where('clas_id', $request->clas_id)
        ->sum('points');

        return response(['data' => $data], 200);
    }

    public function getExams()
    {
        $data = Exam::where('clas_id',auth()->user()->clas_id)->get();

        return response()->json(['data' => $data]);
    }

    public function getGames()
    {
        $data = Game::get();

        return response()->json(['data' => $data]);
    }

}
