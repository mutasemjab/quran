<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Attendance;
use App\Models\ClassTeacher;
use App\Models\Homework;
use App\Models\HomeworkStudent;
use App\Models\Interaction;
use App\Models\Rating;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\LectureClassDate;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;


class TeacherController extends Controller
{
    public function getClasses()
    {
        // Authenticate the teacher
        $teacher = auth()->user();

        if (!$teacher || $teacher->user_type != 2) {
            return response(['message' => 'Unauthorized'], 403);
        }

        // Retrieve the classes associated with the teacher
        $classes = ClassTeacher::where('teacher_id', $teacher->teacher->id)
            ->with('class') // Assuming a relationship exists in the ClassTeacher model
            ->get()
            ->pluck('class'); // Retrieve only the class details

        // Return the classes
        return response([
            'classes' => $classes,
        ], 200);
    }


    // Get all lectures for a specific class
    public function getClassLectures($classId)
    {
        // Authenticate the teacher
        $teacher = auth()->user();

        if (!$teacher || $teacher->user_type != 2) {
            return response(['message' => 'Unauthorized'], 403);
        }

        // Check if the teacher is assigned to the class
        $classAssigned = ClassTeacher::where('teacher_id', $teacher->teacher->id)
            ->where('clas_id', $classId)
            ->exists();

        if (!$classAssigned) {
            return response(['message' => 'You are not assigned to this class'], 403);
        }

        // Get all lectures for this class with their dates
        $lectureDates = LectureClassDate::with('lecture')
            ->where('class_id', $classId)
            ->orderBy('date', 'asc')
            ->get();
        
        // Format the response data
        $lectures = $lectureDates->map(function($lectureDate) {
            return [
                'lecture_date_id' => $lectureDate->id,
                'date' => $lectureDate->formatted_date,
                'lecture' => [
                    'id' => $lectureDate->lecture->id,
                    'type' => $lectureDate->lecture->type,
                    'content_teacher' => $lectureDate->lecture->content_teacher,
                    'content_student' => $lectureDate->lecture->content_student,
                    'video' => $lectureDate->lecture->video,
                    'created_at' => $lectureDate->lecture->created_at,
                    'updated_at' => $lectureDate->lecture->updated_at
                ],
                'is_past' => \Carbon\Carbon::parse($lectureDate->date)->isPast(),
                'is_today' => \Carbon\Carbon::parse($lectureDate->date)->isToday()
            ];
        });

        // Return the lectures
        return response([
            'success' => true,
            'lectures' => $lectures,
        ], 200);
    }

    public function getUsersByClass(Request $request)
    {
        // Validate the input
        $request->validate([
            'class_id' => 'required|exists:clas,id',
        ]);

        // Retrieve all users with the specified class_id
        $users = User::where('clas_id', $request->class_id)->get();

        // Check if any users are found
        if ($users->isEmpty()) {
            return response(['message' => 'No users found for this class'], 404);
        }

        return response([
            'users' => $users,
        ], 200);
    }

    public function recordAttendance(Request $request)
    {
        // Validate input data
        $request->validate([
            'class_id' => 'required|exists:clas,id',
            'attendance' => 'required|array',
            'attendance.*.user_id' => 'required|exists:users,id',
            'attendance.*.type_of_attendance' => 'required|in:1,2,3', // 1: Present, 2: Late with reason, 3: Late without reason
            'attendance.*.description' => 'nullable|string',
            'attendance.*.date_of_attendance' => 'required|date',
        ]);

        // Check if the teacher is assigned to the class
        $teacher = auth()->user();
        $classAssigned = ClassTeacher::where('teacher_id', $teacher->teacher->id)
            ->where('clas_id', $request->class_id)
            ->exists();

        if (!$classAssigned) {
            return response(['message' => 'You are not assigned to this class'], 403);
        }

        // Record attendance for each student
        foreach ($request->attendance as $record) {
            Attendance::updateOrCreate(
                [
                    'user_id' => $record['user_id'],
                    'clas_id' => $request->class_id,
                    'date_of_attendance' => $record['date_of_attendance'],
                ],
                [
                    'day' => \Carbon\Carbon::parse($record['date_of_attendance'])->format('l'),
                    'type_of_attendance' => $record['type_of_attendance'],
                    'description' => $record['description'] ?? null,
                ]
            );
        }

        return response(['message' => 'Attendance recorded successfully'], 200);
    }

    public function recordRatings(Request $request)
    {
        // Validate the input
        $request->validate([
            'class_id' => 'required|exists:clas,id',
            'ratings' => 'required|array',
            'ratings.*.user_id' => 'required|exists:users,id',
            'ratings.*.type_of_rating' => 'required|in:1,2,3,4,5', // 1: Share, 2: Homework, 3: Save, 4: Recitation, 5: Quiz
            'ratings.*.date_of_rating' => 'required|date',
            'ratings.*.rating'=>'required',
        ]);

        // Check if the teacher is assigned to the class
        $teacher = auth()->user();
        $classAssigned = ClassTeacher::where('teacher_id', $teacher->teacher->id)
            ->where('clas_id', $request->class_id)
            ->exists();

        if (!$classAssigned) {
            return response(['message' => 'You are not assigned to this class'], 403);
        }

        // Record ratings for each student
        foreach ($request->ratings as $record) {
            Rating::updateOrCreate(
                [
                    'user_id' => $record['user_id'],
                    'clas_id' => $request->class_id,
                    'date_of_rating' => $record['date_of_rating'],
                    'type_of_rating' => $record['type_of_rating'],
                    'rating'=>$record['rating'],
                ],
                [
                    'day' => \Carbon\Carbon::parse($record['date_of_rating'])->format('l'),
                ]
            );
        }

        return response(['message' => 'Ratings recorded successfully'], 200);
    }


    public function createHomework(Request $request)
    {
        // Validate the input data
        $request->validate([
            'clas_id' => 'required|exists:clas,id',
            'lecture_id' => 'required|exists:lectures,id',
            'type' => 'required|in:1,2,3', // 1: Quran, 2: Manhag, 3: Extra
            'name_of_sura' => 'nullable|string',
            'from' => 'nullable|string',
            'to' => 'nullable|string',
            'description_manhag' => 'nullable|string',
            'description_extra' => 'nullable|string',
            'users' => 'nullable|array', // Optional: List of user IDs
            'users.*' => 'exists:users,id',
        ]);

        // Check if the teacher is assigned to the class
        $teacher = auth()->user();
        $classAssigned = ClassTeacher::where('teacher_id', $teacher->teacher->id)
            ->where('clas_id', $request->clas_id)
            ->exists();

        if (!$classAssigned) {
            return response(['message' => 'You are not assigned to this class'], 403);
        }

        // Create the homework
        $homework = Homework::create([
            'clas_id' => $request->clas_id,
            'lecture_id' => $request->lecture_id,
            'teacher_id' => $teacher->teacher->id,
            'type' => $request->type,
            'name_of_sura' => $request->name_of_sura,
            'from' => $request->from,
            'to' => $request->to,
            'description_manhag' => $request->description_manhag,
            'description_extra' => $request->description_extra,
        ]);

        // Assign homework to users
        if ($request->has('users') && !empty($request->users)) {
            foreach ($request->users as $userId) {
                HomeworkStudent::create([
                    'homework_id' => $homework->id,
                    'user_id' => $userId,
                ]);
            }
        } else {
            // Assign homework to all users in the class if no specific users are provided
            $users = User::where('clas_id', $request->clas_id)->get();
            foreach ($users as $user) {
                HomeworkStudent::create([
                    'homework_id' => $homework->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        return response(['message' => 'Homework created and assigned successfully'], 201);
    }


    public function getHomeworksForLastLecture(Request $request)
    {
        // Validate the input
        $request->validate([
            'clas_id' => 'required|exists:clas,id',
            'lecture_id' => 'required|exists:lectures,id',
            'type' => 'required|in:all,special', // 'all' for all users, 'special' for specific users
        ]);

        // Get the authenticated teacher
        $teacher = auth()->user();

        // Check if the teacher is assigned to the class
        $classAssigned = ClassTeacher::where('teacher_id', $teacher->teacher->id)
            ->where('clas_id', $request->clas_id)
            ->exists();

        if (!$classAssigned) {
            return response(['message' => 'You are not assigned to this class'], 403);
        }

        // Get the last lecture's date for the specified class
        $today = Carbon::today();
        $lastLectureDate = LectureClassDate::where('class_id', $request->clas_id)
            ->where('date', '<', $today)
            ->orderBy('date', 'desc')
            ->first();

        if (!$lastLectureDate) {
            return response(['message' => 'No lectures found for this class before today'], 404);
        }

        // Query homeworks for the last lecture
        $query = Homework::where('clas_id', $request->clas_id)
            ->where('lecture_id', $lastLectureDate->lecture_id) // Match the last lecture
            ->with('homeworkStudents.user');

        // Apply type filter
        if ($request->type === 'special') {
            $query->whereHas('homeworkStudents'); // Ensures homework has specific assigned users
        }

        $homeworks = $query->get();

        if ($homeworks->isEmpty()) {
            return response(['message' => 'No homework found for the last lecture'], 404);
        }

        return response([
            'last_lecture_date' => $lastLectureDate->formatted_date,
            'homeworks' => $homeworks,
        ], 200);
    }



    public function getInteractionsByClass(Request $request)
    {
        // Validate the input
        $request->validate([
            'clas_id' => 'required|exists:clas,id',
        ]);

        // Define the interaction types mapping
        $interactionTypes = [
            1 => 'Pray',
            2 => 'Tasbeeh',
            3 => 'Game',
            4 => 'Strengthening the student',
        ];

        // Get interactions for the specified class
        $interactions = Interaction::where('clas_id', $request->clas_id)
            ->with('user') // Load user details
            ->get()
            ->groupBy('user_id');

        if ($interactions->isEmpty()) {
            return response(['message' => 'No interactions found for this class'], 404);
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

    public function storeExam(Request $request)
    {
        try {
            // Validate incoming request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'clas_id' => 'required|exists:clas,id',
                'lecture_id' => 'required|exists:lectures,id',
                'exam_date' => 'required|date',
            ]);

            // Create a new Exam record
            $exam = Exam::create($validatedData);

            // Return success response
            return response()->json([
                'message' => 'Exam created successfully',
                'exam' => $exam
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'users' => 'required|array',
            'users.*.name' => 'required|string|max:255',
            'users.*.grade' => 'required',
            'users.*.lecture_id' => 'required|exists:lectures,id',
            'users.*.clas_id' => 'required|exists:clas,id',
            'users.*.user_id' => 'required|exists:users,id',
        ]);

        $grades = [];

        foreach ($validatedData['users'] as $userData) {
            $grades[] = Grade::create([
                'name' => $userData['name'],
                'grade' => $userData['grade'],
                'lecture_id' => $userData['lecture_id'],
                'clas_id' => $userData['clas_id'],
                'user_id' => $userData['user_id'],
            ]);
        }

        return response()->json(['data' => $grades, 'message' => 'Grades recorded successfully.'], 200);
    }


    public function destroy($id)
    {
        $grade = Grade::find($id);

        if (!$grade) {
            return response()->json(['message' => 'Grade not found'], 404);
        }

        $grade->delete();

        return response()->json(['message' => 'Grade deleted successfully']);
    }
}