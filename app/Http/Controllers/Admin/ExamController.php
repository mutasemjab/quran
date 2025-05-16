<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Exam;
use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamController extends Controller
{
      // Display a list of exams
      public function index()
      {
          $exams = Exam::with('class')->get();
          return view('admin.exams.index', compact('exams'));
      }
  
      // Show form to create a new exam
      public function create()
      {
          $classes = Clas::all(); // Assuming Clas is the class model
          return view('admin.exams.create', compact('classes'));
      }
  
      // Store a new exam
      public function store(Request $request)
      {
          $request->validate([
              'name' => 'required|string|max:255',
              'clas_id' => 'required|exists:clas,id',
              'exam_date' => 'required|date',
          ]);
  
          Exam::create($request->only(['name', 'clas_id', 'exam_date']));
  
          return redirect()->route('exams.index')->with('success', 'Exam created successfully');
      }
  
      // Show form to add questions to an exam
      public function addQuestions($examId)
      {
          $exam = Exam::findOrFail($examId);
          return view('admin.exams.add_questions', compact('exam'));
      }
  
      // Store questions for an exam
      public function storeQuestions(Request $request, $examId)
      {
          $request->validate([
              'questions' => 'required|array',
              'questions.*.question_text' => 'required|string|max:255',
              'questions.*.type' => 'required|in:true_false,multiple_choice',
              'questions.*.correct_answer' => 'required|string|max:255',
              'questions.*.option_1' => 'nullable|string|max:255',
              'questions.*.option_2' => 'nullable|string|max:255',
              'questions.*.option_3' => 'nullable|string|max:255',
              'questions.*.option_4' => 'nullable|string|max:255',
          ]);
  
          foreach ($request->questions as $question) {
              Question::create([
                  'exam_id' => $examId,
                  'question_text' => $question['question_text'],
                  'type' => $question['type'],
                  'option_1' => $question['option_1'] ?? null,
                  'option_2' => $question['option_2'] ?? null,
                  'option_3' => $question['option_3'] ?? null,
                  'option_4' => $question['option_4'] ?? null,
                  'correct_answer' => $question['correct_answer'],
              ]);
          }
  
          return redirect()->route('exams.index')->with('success', 'Questions added successfully');
      }


    public function destroy($id)
    {
        // Find the exam record by ID
        $exam = Exam::findOrFail($id);

        // Delete the record
        $exam->delete();

        // Redirect back with a success message
        return redirect()->route('exams.index')->with('success', __('messages.deleted_successfully'));
    }



}
