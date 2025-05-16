<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Models\Clas;
use App\Models\Receivable;
use Illuminate\Support\Facades\File;
use App\Imports\UsersImport;

class StudentController extends Controller
{

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Students imported successfully!');
    }



    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }



    public function index(Request $request)
    {
        $query = User::where('user_type', 1); // Assuming user_type 1 is for students

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where(\DB::raw('CONCAT_WS(" ", `name`, `email`)'), 'like', '%' . $request->search . '%');
            });
        }


        $data = $query->paginate(PAGINATION_COUNT);

        $searchQuery = $request->search;

        return view('admin.students.index', compact('data', 'searchQuery'));
    }


    public function create()
    {
        $classes = Clas::get();
        return view('admin.students.create', compact('classes'));
    }



    public function store(Request $request)
    {
        try {

            $student = new User();
            $student->name = $request->get('name');
            $student->email = $request->get('email');
            $student->username = $request->get('username');
            $student->phone = $request->get('phone');

            $student->clas_id = $request->get('class');
            $student->user_type = 1;



            if ($request->activate) {
                $student->activate = $request->get('activate');
            }
            $student->password = Hash::make($request->password);

            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $student->photo = $the_file_path;
            }
            if ($student->save()) {

                return redirect()->route('students.index')->with(['success' => 'Student created']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }


    public function show($id)
    {
        $student = User::where('user_type', 1)->findOrFail($id);
        return view('admin.students.show', compact('student'));
    }


    public function edit($id)
    {
        if (auth()->user()->can('student-edit')) {
            $classes = Clas::get();
            $data = User::where('user_type', 1)->findorFail($id);
            return view('admin.students.edit', compact('data', 'classes'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $student = User::where('user_type', 1)->findOrFail($id);
        try {
            $student->name = $request->get('name');
            if ($request->password) {
                $student->password = Hash::make($request->password);
            }
            $student->email = $request->get('email');
            $student->username = $request->get('username');
            $student->phone = $request->get('phone');

            $student->clas_id = $request->get('class');

            if ($request->activate) {
                $student->activate = $request->get('activate');
            }


            if ($request->has('photo')) {
                // Delete the old photo from the file system
                $filePath = base_path('assets/admin/uploads/' . $student->photo);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }

                // Upload the new image
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $student->photo = $the_file_path;
            }

            if ($student->save()) {
                return redirect()->route('students.index')->with(['success' => 'Student updated']);
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
