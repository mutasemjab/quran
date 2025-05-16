<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Grade;
use App\Models\Lecture;
use App\Models\lecture;
use App\Models\User;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {

        $data = Grade::paginate(PAGINATION_COUNT);

        return view('admin.grades.index', ['data' => $data]);
    }

    public function edit($id)
    {
        // Fetch grade data by ID
        $data = Grade::findOrFail($id);

        // Fetch all classes for the dropdown
        $classes = Clas::all(); // Replace `Clas` with your actual model for classes

        // Fetch all lectures for the dropdown
        $lectures = Lecture::all(); // Replace `lecture` with your actual model for lectures

        // Fetch all students for the dropdown
        $users = User::where('user_type', 1)->get(); // Assuming user_type 1 is for students

        // Return the edit view with the fetched data
        return view('admin.grades.edit', compact('data', 'classes', 'lectures', 'users'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|numeric|min:0|max:100', // Example: Grade between 0 and 100
            'clas' => 'required|exists:clas,id', // Ensure class ID exists in the `clas` table
            'lecture' => 'required|exists:lectures,id', // Ensure lecture ID exists in the `lectures` table
            'user' => 'required|exists:users,id', // Ensure student ID exists in the `users` table
        ]);

        // Find the grade record by ID
        $grade = Grade::findOrFail($id);

        // Update the grade record with the validated data
        $grade->update([
            'name' => $validated['name'],
            'grade' => $validated['grade'],
            'clas_id' => $validated['clas'],
            'lecture_id' => $validated['lecture'],
            'user_id' => $validated['user'],
        ]);

        // Redirect back with a success message
        return redirect()->route('grades.index')->with('success', __('messages.updated_successfully'));
    }

    public function destroy($id)
    {
        // Find the grade record by ID
        $grade = Grade::findOrFail($id);

        // Delete the record
        $grade->delete();

        // Redirect back with a success message
        return redirect()->route('grades.index')->with('success', __('messages.deleted_successfully'));
    }



}
