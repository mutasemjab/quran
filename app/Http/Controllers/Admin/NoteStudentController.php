<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Lesson;
use App\Models\NoteStudent;
use App\Models\User;
use Illuminate\Http\Request;

class NoteStudentController extends Controller
{
    public function index(Request $request)
    {
        $data = NoteStudent::paginate(PAGINATION_COUNT); 

        return view('admin.noteStudents.index', compact('data'));
    }

    public function edit($id)
    {
        // Fetch note data by ID
        $data = NoteStudent::findOrFail($id);

        // Fetch all classes for the dropdown
        $classes = Clas::all(); // Replace `Clas` with your actual model for classes

        // Fetch all lessons for the dropdown
        $lessons = Lesson::all(); // Replace `Lesson` with your actual model for lessons

        // Fetch all students for the dropdown
        $users = User::where('user_type', 1)->get(); // Assuming user_type 1 is for students

        // Return the edit view with the fetched data
        return view('admin.noteStudents.edit', compact('data', 'classes', 'lessons', 'users'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'clas' => 'required|exists:clas,id', // Ensure class ID exists in the `clas` table
            'lesson' => 'required|exists:lessons,id', // Ensure lesson ID exists in the `lessons` table
            'user' => 'required|exists:users,id', // Ensure student ID exists in the `users` table
        ]);

        // Find the note record by ID
        $noteStudent = NoteStudent::findOrFail($id);

        // Update the note record with the validated data
        $noteStudent->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'clas_id' => $validated['clas'],
            'lesson_id' => $validated['lesson'],
            'user_id' => $validated['user'],
        ]);

        // Redirect back with a success message
        return redirect()->route('noteStudents.index')->with('success', __('messages.updated_successfully'));
    }

    public function destroy($id)
    {
        // Find the note student record by ID
        $noteStudent = NoteStudent::findOrFail($id);

        // Delete the record
        $noteStudent->delete();

        // Redirect back with a success message
        return redirect()->route('noteStudents.index')->with('success', __('messages.deleted_successfully'));
    }



}
