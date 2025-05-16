<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LessonController extends Controller
{
    public function index()
    {

        $data = Lesson::get();

        return view('admin.lessons.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('lesson-add')) {
            $classes = Clas::get();
            return view('admin.lessons.create', compact('classes'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {
        if (auth()->user()->can('lesson-add')) {
            try {

                $lesson = new Lesson();
                $lesson->name = $request->get('name');

                if ($request->has('pdf')) {
                    $the_file_path = uploadImage('assets/admin/uploads', $request->pdf);
                    $lesson->pdf = $the_file_path;
                 }

                if ($lesson->save()) {

                    // Save the teacher and class relationship
                    if ($request->has('clas_id')) {
                        foreach ($request->clas_id as $class_id) {
                            \DB::table('class_lessons')->insert([
                                'lesson_id' => $lesson->id,
                                'clas_id' => $class_id,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }

                    return redirect()->route('lessons.index')->with(['success' => 'lesson created']);
                } else {
                    return redirect()->back()->with(['error' => 'Something wrong']);
                }
            } catch (\Exception $ex) {
                return redirect()->back()
                    ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                    ->withInput();
            }
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function edit($id)
    {
        if (auth()->user()->can('lesson-edit')) {
            $classes = Clas::get();
            $data = Lesson::findorFail($id);
            $lessonClasses = \DB::table('class_lessons')->where('lesson_id', $id)->pluck('clas_id')->toArray(); // Fetch the IDs of the associated classes
            return view('admin.lessons.edit', compact('data', 'classes', 'lessonClasses'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->can('lesson-edit')) {
            $lesson = Lesson::findorFail($id);
            try {
                $lesson->name = $request->get('name');


                if ($request->has('pdf')) {
                    // Delete the old pdf from the file system
                    $filePath = base_path('assets/admin/uploads/' . $lesson->pdf);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                
                    // Assuming you want to delete the reference to the old pdf from the teacher model
                    // Upload the new image
                    $the_file_path = uploadImage('assets/admin/uploads', $request->pdf);
                
                    // Save the new pdf path to the teacher's pdf field
                    $lesson->pdf = $the_file_path;
                
                    // Save the pdf model to update the database record
                    $lesson->save();
                }
            

                if ($lesson->save()) {

                    \DB::table('class_lessons')->where('lesson_id', $lesson->id)->delete();

                    // Insert new class relationships
                    if ($request->has('clas_id')) {
                        foreach ($request->clas_id as $class_id) {
                            \DB::table('class_lessons')->insert([
                                'lesson_id' => $lesson->id,
                                'clas_id' => $class_id,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }


                    return redirect()->route('lessons.index')->with(['success' => 'lesson update']);
                } else {
                    return redirect()->back()->with(['error' => 'Something wrong']);
                }
            } catch (\Exception $ex) {
                return redirect()->back()
                    ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                    ->withInput();
            }
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return redirect()->route('lessons.index')->with(['success' => 'lesson Delete']);
    }
}
