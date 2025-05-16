<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ClasController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\AhadeethController;
use App\Http\Controllers\Admin\AhadeethClassController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\ReceivableController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\LectureController;
use App\Http\Controllers\Admin\NoteStudentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ParentStudentController;
use App\Http\Controllers\Admin\AthkarController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\SeeraController;
use App\Http\Controllers\Reports\OrderReportController;
use App\Http\Controllers\Reports\ProductReportController;
use App\Http\Controllers\Reports\TaxReportController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Models\Permission;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

define('PAGINATION_COUNT',11);
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {




 Route::group(['prefix'=>'admin','middleware'=>'auth:admin'],function(){
 Route::get('/',[DashboardController::class,'index'])->name('admin.dashboard');
 Route::get('logout',[LoginController::class,'logout'])->name('admin.logout');


 // other route
 Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
Route::post('students/import', [StudentController::class, 'import'])->name('students.import');

 // other route
 Route::get('teachers/export', [TeacherController::class, 'export'])->name('teachers.export');
Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');

Route::delete('/class/weekly-date/{id}', [ClasController::class, 'removeWeeklyDate'])->name('class.removeWeeklyDate');
Route::post('/class/weekly-date/{id}/assign-lesson', [ClasController::class, 'assignLesson'])->name('class.assignLesson');

Route::get('exams/{exam}/questions', [ExamController::class, 'addQuestions'])->name('exams.addQuestions');
Route::post('exams/{exam}/questions', [ExamController::class, 'storeQuestions'])->name('exams.storeQuestions');

/*         start  update login admin                 */
Route::get('/admin/edit/{id}',[LoginController::class,'editlogin'])->name('admin.login.edit');
Route::post('/admin/update/{id}',[LoginController::class,'updatelogin'])->name('admin.login.update');
/*         end  update login admin                */

/// Role and permission
Route::resource('employee', 'App\Http\Controllers\Admin\EmployeeController',[ 'as' => 'admin']);
Route::get('role', 'App\Http\Controllers\Admin\RoleController@index')->name('admin.role.index');
Route::get('role/create', 'App\Http\Controllers\Admin\RoleController@create')->name('admin.role.create');
Route::get('role/{id}/edit', 'App\Http\Controllers\Admin\RoleController@edit')->name('admin.role.edit');
Route::patch('role/{id}', 'App\Http\Controllers\Admin\RoleController@update')->name('admin.role.update');
Route::post('role', 'App\Http\Controllers\Admin\RoleController@store')->name('admin.role.store');
Route::post('admin/role/delete', 'App\Http\Controllers\Admin\RoleController@delete')->name('admin.role.delete');

Route::get('/permissions/{guard_name}', function($guard_name){
    return response()->json(Permission::where('guard_name',$guard_name)->get());
});


/*         start  setting                */
Route::get('/setting/index',[SettingController::class,'index'])->name('admin.setting.index');
Route::get('/setting/create',[SettingController::class,'create'])->name('admin.setting.create');
Route::post('/setting/store',[SettingController::class,'store'])->name('admin.setting.store');
Route::get('/setting/edit/{id}',[SettingController::class,'edit'])->name('admin.setting.edit');
Route::post('/setting/update/{id}',[SettingController::class,'update'])->name('admin.setting.update');

/*         end  setting                */


// Notification
Route::get('/notifications/create',[NotificationController::class,'create'])->name('notifications.create');
Route::post('/notifications/send',[NotificationController::class,'send'])->name('notifications.send');



Route::prefix('pages')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('pages.index');
    Route::get('/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('/store', [PageController::class, 'store'])->name('pages.store');
    Route::get('/edit/{id}', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/update/{id}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('/delete/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
});


// Resource Route
Route::resource('class', ClasController::class);
Route::resource('athkars', AthkarController::class);
Route::resource('parentStudents', ParentStudentController::class);
Route::resource('teachers', TeacherController::class);
Route::resource('students', StudentController::class);
Route::resource('lectures', LectureController::class);
Route::resource('attendances', AttendanceController::class);
Route::resource('ratings', RatingController::class);
Route::resource('grades', GradeController::class);
Route::resource('exams', ExamController::class);
Route::resource('seeras', SeeraController::class);
Route::resource('games', GameController::class);


});
});



Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>'guest:admin'],function(){
    Route::get('login',[LoginController::class,'show_login_view'])->name('admin.showlogin');
    Route::post('login',[LoginController::class,'login'])->name('admin.login');

});







