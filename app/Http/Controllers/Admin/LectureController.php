<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Lecture;
use App\Models\LectureClassDate;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LectureController extends Controller
{
    public function index()
    {

        $data = Lecture::get();

        return view('admin.lectures.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('lecture-add')) {
            $classes = Clas::get();
            return view('admin.lectures.create', compact('classes'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {
        if (auth()->user()->can('lecture-add')) {
            try {

                $lecture = new Lecture();
                $lecture->type = $request->get('type');
                $lecture->content_teacher = $request->get('content_teacher');
                $lecture->content_student = $request->get('content_student');
                // $lecture->class_id = $request->get('classes');
                $classes = $request->input('classes', []);
                $dates = $request->input('dates', []);

                $lectures = [];
                
                if ($lecture->save()) {
                    foreach ($classes as $index => $classId) {
                        $date = $dates[$index] ?? null;
    
                        // Skip rows with empty date
                        if (empty($date)) {
                            continue;
                        }
    
                        $lectures[] = [
                            'lecture_id' => $lecture->id,
                            'class_id' => $classId,
                            'date' => $date,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    // Save the lectures to the database
                    LectureClassDate::insert($lectures);

                    return redirect()->route('lectures.index')->with(['success' => 'lecture created']);
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
        if (auth()->user()->can('lecture-edit')) {
            $classes = Clas::get();
            $lecture = Lecture::with('classDates')->findOrFail($id);            
            return view('admin.lectures.edit', compact('lecture', 'classes'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->can('lecture-edit')) {
            $lecture = Lecture::findorFail($id);
            try {

                $lecture->type = $request->get('type');
                $lecture->content_teacher = $request->get('content_teacher');
                $lecture->content_student = $request->get('content_student');
                $classes = $request->input('classes', []);
                $dates = $request->input('dates', []);

                if ($lecture->save()) {
                // Fetch existing data
                $existing = LectureClassDate::where('lecture_id', $lecture->id)->get()->keyBy('class_id');

                $submitted = [];
                foreach ($classes as $index => $classId) {
                    $date = $dates[$index] ?? null;
                    if (empty($date)) continue;

                    $submitted[$classId] = $date;

                    if (isset($existing[$classId])) {
                        // Update if date has changed
                        if ($existing[$classId]->date !== $date) {
                            $existing[$classId]->date = $date;
                            $existing[$classId]->updated_at = now();
                            $existing[$classId]->save();
                        }
                        // Remove from existing so remaining are deletions
                        unset($existing[$classId]);
                    } else {
                        // Insert new
                        LectureClassDate::create([
                            'lecture_id' => $lecture->id,
                            'class_id' => $classId,
                            'date' => $date,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Delete the remaining old records not in submitted data
                foreach ($existing as $toDelete) {
                    $toDelete->delete();
                }
                    return redirect()->route('lectures.index')->with(['success' => 'lecture update']);
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
        $lecture = Lecture::findOrFail($id);
        $lecture->delete();

        return redirect()->route('lectures.index')->with(['success' => 'lecture Delete']);
    }
}
