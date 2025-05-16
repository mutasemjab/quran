<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Attendance;

use App\Models\ClassTeacher;
use App\Models\Homework;
use App\Models\HomeworkStudent;
use App\Models\Interaction;
use App\Models\ParentStudentReleation;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ParentStudentController extends Controller
{

    public function addChild(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'photo' => 'nullable|string',
            'clas_id' => 'nullable|exists:clas,id',
        ]);

        $parent = auth()->user();

        if ($parent->user_type != 3) { // Ensure the user is a parent
            return response()->json(['error' => 'Only parents can add children'], 403);
        }

        // Create new child user
        $child = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'activate' => 2,
            'family_id' => auth()->user()->id,
            'photo' => $request->photo,
            'user_type' => 1, // 1 for normal user (student)
            'clas_id' => $request->clas_id,
        ]);

        // Create parent-child relation
        ParentStudentReleation::create([
            'parent_student_id' => $parent->parentStudent->id,
            'user_id' => $child->id,
        ]);

        return response()->json([
            'message' => 'Child added successfully',
            'child' => $child
        ], 201);
    }

    public function getChildren()
    {
        $parent = auth()->user();

        if ($parent->user_type != 3) {
            return response()->json(['error' => 'Only parents can view children'], 403);
        }

        $children = User::whereIn('id', ParentStudentReleation::where('parent_student_id', $parent->parentStudent->id)
            ->pluck('user_id'))
            ->get();

        return response()->json($children);
    }


    public function getInteractionsForMyChild(Request $request)
    {
        // Validate the input
        $request->validate([
            'clas_id' => 'required|exists:clas,id',
            'user_id' => 'nullable|exists:users,id', // Make user_id optional
        ]);

        // Define the interaction types mapping
        $interactionTypes = [
            1 => 'Pray',
            2 => 'athkar_after_pray',
            3 => 'athkar',
            4 => 'asmaa_allah_alhosna',
            5 => 'Game',
            6 => 'taqweya',
        ];

        // Query interactions for the specified class and user (if provided)
        $query = Interaction::where('clas_id', $request->clas_id)
            ->with('user'); // Load user details

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $interactions = $query->get()->groupBy('user_id');

        if ($interactions->isEmpty()) {
            return response(['message' => 'No interactions found for this class or user'], 404);
        }

        // Format the response
        $data = [];
        foreach ($interactions as $userId => $userInteractions) {
            $userData = [
                'user_id' => $userId,
                'user_name' => $userInteractions->first()->user->name ?? 'Unknown',
                'interactions' => $userInteractions->map(function ($interaction) use ($interactionTypes) {
                    return [
                        'type_of_interaction' => $interactionTypes[$interaction->type_of_interaction] ?? 'Unknown',
                        'points' => $interaction->points,
                    ];
                }),
            ];
            $data[] = $userData;
        }

        return response(['data' => $data], 200);
    }

    public function getMyChildGrade(Request $request)
    {
          // Validate the input
          $request->validate([
            'user_id' => 'nullable|exists:users,id', // Make user_id optional
        ]);

        // Fetch grades for the authenticated user
        $grades = Grade::with('lecture')->where('user_id', $request->user_id)->get();

        return response()->json(['data' => $grades]);
    }

    public function getRatingsForMyChild(Request $request)
    {
        // Validate the input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'class_id' => 'nullable|exists:clas,id', // Optional filter by class
        ]);

        // Define rating types
        $ratingTypes = [
            1 => 'Share',
            2 => 'Homework',
            3 => 'Save',
            4 => 'Recitation',
            5 => 'Quiz',
        ];

        // Query ratings for the specified user
        $query = Rating::where('user_id', $request->user_id);

        // Optionally filter by class
        if ($request->has('class_id')) {
            $query->where('clas_id', $request->class_id);
        }

        $ratings = $query->get();

        if ($ratings->isEmpty()) {
            return response(['message' => 'No ratings found for this user'], 404);
        }

        // Format response
        $data = $ratings->map(function ($rating) use ($ratingTypes) {
            return [
                'class_id' => $rating->clas_id,
                'type_of_rating' => $ratingTypes[$rating->type_of_rating] ?? 'Unknown',
                'date_of_rating' => $rating->date_of_rating,
                'day' => $rating->day,
                'rating' => $rating->rating,
            ];
        });

        return response(['data' => $data], 200);
    }


    public function getHomeworksForMyChild(Request $request)
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




}
