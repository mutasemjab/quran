<?php

namespace App\Http\Controllers\Api\v1\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\User\AuthController;
use App\Http\Controllers\Api\v1\User\ParentStudentController;
use App\Http\Controllers\Api\v1\User\AttendanceController;
use App\Http\Controllers\Api\v1\User\ExamController;
use App\Http\Controllers\Api\v1\User\GradeController;
use App\Http\Controllers\Api\v1\User\ClasController;
use App\Http\Controllers\Api\v1\User\StudentController;
use App\Http\Controllers\Api\v1\User\WorkPaperController;
use App\Http\Controllers\Api\v1\User\UploadPhotoVoiceController;
use App\Http\Controllers\Api\v1\User\TeacherController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route unAuth
Route::group(['prefix' => 'v1/user'], function () {

    //---------------- Auth --------------------//
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/settings', [SettingController::class, 'index']);

    // Auth Route
    Route::group(['middleware' => ['auth:user-api']], function () {

         Route::get('/active', [AuthController::class, 'active']);

        // image for chat
        Route::get('/uploadPhotoVoice', [UploadPhotoVoiceController::class,'index']);
        Route::post('/uploadPhotoVoice', [UploadPhotoVoiceController::class,'store']);

        Route::post('/update_profile', [AuthController::class, 'updateProfile']);
        Route::post('/delete_account', [AuthController::class, 'deleteAccount']);
        Route::get('/userProfile', [AuthController::class, 'userProfile']);
        Route::get('/pages/{type}', [PageController::class,'index']);

        //Notification
        Route::get('/notifications', [AuthController::class, 'notifications']);
        Route::post('/notifications', [AuthController::class, 'sendToUser']);


        // Teacher
        Route::get('/teacher/classes', [TeacherController::class, 'getClasses']);
        Route::get('/teacher/classes/{classId}/lectures', [TeacherController::class, 'getClassLectures']);
        Route::post('/class/users', [TeacherController::class, 'getUsersByClass']);
        Route::post('/class/attendance', [TeacherController::class, 'recordAttendance']);
        Route::post('/class/ratings', [TeacherController::class, 'recordRatings']);
        Route::post('/class/homework', [TeacherController::class, 'createHomework']);
        Route::post('/class/homeworks/last-lecture', [TeacherController::class, 'getHomeworksForLastLecture']);
        Route::post('/interactions/class', [TeacherController ::class, 'getInteractionsByClass']);
        Route::post('/exams', [TeacherController::class, 'storeExam']);
        Route::post('/grades', [TeacherController::class, 'store']);
        Route::delete('/grades/{id}', [TeacherController::class, 'destroy']);

        // CLASSES AND Lectures
        Route::get('/next-lecture/{classId}', [ClasController::class, 'getNextLecture']);
        Route::get('/class/{classId}/lectures', [ClasController::class, 'getAllLectures']);
         Route::get('/classess', [ClasController::class, 'getClassess']);


        // Parent
        Route::post('/parent/add-child', [ParentStudentController::class, 'addChild']);
        Route::get('/parent/children', [ParentStudentController::class, 'getChildren']);
        Route::get('/interactions/my-child', [ParentStudentController::class, 'getInteractionsForMyChild']);
        Route::get('/parent/grades', [ParentStudentController::class, 'getMyChildGrade']);
        Route::get('/getRatingsForMyChild', [ParentStudentController::class, 'getRatingsForMyChild']);
        Route::get('/getHomeworksForMyChild', [ParentStudentController::class, 'getHomeworksForMyChild']);



        //Students
        Route::post('/interactions/store', [StudentController::class, 'storeInteraction']);
        Route::get('/getAthkar', [StudentController::class, 'getAthkar']);
        Route::get('/class/{clas_id}/lectures', [StudentController::class, 'getClassLectures']);
        Route::get('/getSeera', [StudentController::class, 'getSeera']);
        Route::get('/getHomeworks', [StudentController::class, 'getHomeworks']);
        Route::post('/homework-answer', [StudentController::class, 'store']);
        Route::get('/homework-answer', [StudentController::class, 'getAnswers']);
        Route::get('/getInteractions', [StudentController::class, 'getInteractions']);
        Route::get('/getExams', [StudentController::class, 'getExams']);
        Route::get('/getGames', [StudentController::class, 'getGames']);

    });


});
