<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\ParentStudent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Imports\ParentStudentsImport;
use Maatwebsite\Excel\Facades\Excel;

class ParentStudentController extends Controller
{

    public function index(Request $request)
    {
        // Start the query
        $query = ParentStudent::query();

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
        return view('admin.parentStudents.index', compact('data', 'searchQuery'));
    }



    public function create()
    {
        $users = User::where('user_type',1)->get();
       return view('admin.parentStudents.create',compact('users'));
    }



   public function store(Request $request)
{
    try {
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->username = $request->get('username');
        $user->password = Hash::make($request->password);
        $user->user_type = 3;
        $user->save();

        $ParentStudent = new ParentStudent();
        $ParentStudent->email = $request->get('email');
        $ParentStudent->username = $request->get('username');
        $ParentStudent->user_id = $user->id;

        if ($request->activate) {
            $ParentStudent->activate = $request->get('activate');
        }
        $ParentStudent->password = Hash::make($request->password);

        if ($request->has('photo')) {
            $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
            $ParentStudent->photo = $the_file_path;
        }

        if ($ParentStudent->save()) {
            // Save the ParentStudent and class relationship
            if ($request->has('user_id')) {
                foreach ($request->user_id as $user_id) {
                    \DB::table('parent_student_releations')->insert([
                        'parent_student_id' => $ParentStudent->id,
                        'user_id' => $user_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            return redirect()->route('parentStudents.index')->with(['success' => 'ParentStudent created']);
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
            if (auth()->user()->can('parentStudent-edit')) {
                $users = User::where('user_type',1)->get();
                $data = ParentStudent::findOrFail($id);
                $ParentStudentUsers = \DB::table('parent_student_releations')->where('parent_student_id', $id)->pluck('user_id')->toArray();

                return view('admin.parentStudents.edit', compact('data','ParentStudentUsers','users'));
            } else {
                return redirect()->back()->with('error', "Access Denied");
            }
        }


      public function update(Request $request, $id)
        {
            $ParentStudent = ParentStudent::findOrFail($id);
            $user = User::where('user_type', 3)->findOrFail($ParentStudent->user_id);

            try {
                $ParentStudent->name = $request->get('name');
                $ParentStudent->email = $request->get('email');
                $ParentStudent->username = $request->get('username');
                // update for table user
                $user->email = $request->get('email');
                $user->username = $request->get('username');

                if ($request->password) {
                    $ParentStudent->password = Hash::make($request->password);
                    $user->password = Hash::make($request->password);
                }

                if ($request->activate) {
                    $user->activate = $request->get('activate');
                }

                if ($request->has('photo')) {
                    $filePath = base_path('assets/admin/uploads/' . $ParentStudent->photo);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                    $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                    $ParentStudent->photo = $the_file_path;
                }

                if ($ParentStudent->save() && $user->save()) {
                    // Update class relationships
                    \DB::table('parent_student_releations')->where('parent_student_id', $ParentStudent->id)->delete();
                    if ($request->has('user_id')) {
                        foreach ($request->user_id as $user_id) {
                            \DB::table('class_ParentStudents')->insert([
                                'parent_student_id' => $ParentStudent->id,
                                'user_id' => $user_id,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }


                    return redirect()->route('parentStudents.index')->with(['success' => 'ParentStudent updated']);
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
