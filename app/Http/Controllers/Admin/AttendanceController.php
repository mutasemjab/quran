<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Clas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $data = Attendance::paginate(PAGINATION_COUNT); 

        return view('admin.attendances.index', compact('data'));
    }

    public function edit($id)
    {
        // Fetch attendance data by ID
        $data = Attendance::findOrFail($id);

        // Fetch all classes for the dropdown
        $classes = Clas::all(); // Replace with your actual model for classes

        // Fetch all students for the dropdown
        $users = User::where('user_type', 1)->get(); // Assuming user_type 1 is for students

        // Return the edit view with the fetched data
        return view('admin.attendances.edit', compact('data', 'classes', 'users'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date_of_attendance' => 'required|date',
            'type_of_attendance' => 'required|in:1,2,3', 
            'description' => 'nullable|string',
            'clas' => 'required|exists:clas,id', // Replace `clas` with your table name for classes
            'user' => 'required|exists:users,id', // Assuming `users` table stores students
        ]);

        // Find the attendance record by ID
        $attendance = Attendance::findOrFail($id);
        // Convert date_of_attendance to Carbon instance and get the day name
        $dayName = Carbon::parse($validated['date_of_attendance'])->format('l');

        // Update the attendance record with the validated data
        $attendance->update([
            'day' =>  $dayName,
            'date_of_attendance' => $validated['date_of_attendance'],
            'type_of_attendance' => $validated['type_of_attendance'],
            'description' => $validated['description'],
            'clas_id' => $validated['clas'],
            'user_id' => $validated['user'],
        ]);

        // Redirect back with a success message
        return redirect()->route('attendances.index')->with('success', __('messages.updated_successfully'));
    }

    public function destroy($id)
    {
        // Find the attendance record by ID
        $attendance = Attendance::findOrFail($id);

        // Delete the record
        $attendance->delete();

        // Redirect back with a success message
        return redirect()->route('attendances.index')->with('success', __('messages.deleted_successfully'));
    }




}
