<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Imports\TeachersImport;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new TeachersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Teachers imported successfully!');
    }





    public function index(Request $request)
    {
        // Start the query
        $query = Teacher::query();

        // Apply search filtering
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where(\DB::raw('CONCAT_WS(" ", `name`, `email`)'), 'like', '%' . $request->search . '%');
            });
        }

        // Paginate the results
        $data = $query->paginate(PAGINATION_COUNT);

        // Capture the search query
        $searchQuery = $request->search;

        // Return the view with data
        return view('admin.teachers.index', compact('data', 'searchQuery'));
    }



    public function create()
    {
        $classes = Clas::get();
        return view('admin.teachers.create', compact('classes'));
    }



    public function store(Request $request)
    {
        try {
            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->username = $request->get('username');
            $user->phone = $request->get('phone');
            $user->password = Hash::make($request->password);
            $user->user_type = 2;
            $user->clas_id = $request->get('classTeacher');
             if ($request->activate) {
                $user->activate = $request->get('activate');
            }
            $user->save();

            $teacher = new Teacher();
            $teacher->name = $request->get('name');
            $teacher->email = $request->get('email');
            $teacher->username = $request->get('username');
            $teacher->phone = $request->get('phone');
            $teacher->user_id = $user->id;

           
            
            $teacher->password = Hash::make($request->password);

            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $teacher->photo = $the_file_path;
            }

            if ($teacher->save()) {
                // Save the teacher and class relationship
                if ($request->has('clas_id')) {
                    foreach ($request->clas_id as $class_id) {
                        \DB::table('class_teachers')->insert([
                            'teacher_id' => $teacher->id,
                            'clas_id' => $class_id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }

                return redirect()->route('teachers.index')->with(['success' => 'Teacher created']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
        }
    }





    public function edit($id)
    {
        if (auth()->user()->can('teacher-edit')) {
            $classes = Clas::get();
            $data = Teacher::findOrFail($id);
            $teacherClasses = \DB::table('class_teachers')->where('teacher_id', $id)->pluck('clas_id')->toArray();
            return view('admin.teachers.edit', compact('data', 'classes', 'teacherClasses'));
        } else {
            return redirect()->back()->with('error', "Access Denied");
        }
    }


    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = User::where('user_type', 2)->findOrFail($teacher->user_id);

        try {
            $teacher->name = $request->get('name');
            $teacher->email = $request->get('email');
            $teacher->username = $request->get('username');
            $teacher->phone = $request->get('phone');
            $user->email = $request->get('email');
            $user->username = $request->get('username');
            $user->phone = $request->get('phone');
            if ($request->password) {
                $teacher->password = Hash::make($request->password);
                $user->password = Hash::make($request->password);
            }

            if ($request->activate) {
                $user->activate = $request->get('activate');
            }

            if ($request->has('photo')) {
                $filePath = base_path('assets/admin/uploads/' . $teacher->photo);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $teacher->photo = $the_file_path;
            }

            if ($teacher->save() && $user->save()) {
                // Update class relationships
                \DB::table('class_teachers')->where('teacher_id', $teacher->id)->delete();
                if ($request->has('clas_id')) {
                    foreach ($request->clas_id as $class_id) {
                        \DB::table('class_teachers')->insert([
                            'teacher_id' => $teacher->id,
                            'clas_id' => $class_id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }

                return redirect()->route('teachers.index')->with(['success' => 'Teacher updated']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
        }
    }
}
