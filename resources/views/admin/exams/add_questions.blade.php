@extends('layouts.admin')
@section('title')
    {{ __('messages.Add Questions') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">{{ __('messages.Add Questions to Exam') }} - {{ $exam->name }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('exams.storeQuestions', $exam->id) }}" method="POST" id="questions-form">
                @csrf
                <div id="questions-container">
                    <!-- Questions will be dynamically added here -->
                </div>
                <div class="form-group text-center mt-4">
                    <button type="button" id="add-question" class="btn btn-secondary">{{ __('messages.Add Question') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.Save Questions') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let questionCount = 0;

            // Add question block
            document.getElementById("add-question").addEventListener("click", function () {
                questionCount++;
                const questionBlock = `
                    <div class="question-block border p-3 mb-3">
                        <div class="form-group">
                            <label>{{ __('messages.Question Text') }}</label>
                            <textarea name="questions[${questionCount}][question_text]" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>{{ __('messages.Question Type') }}</label>
                            <select name="questions[${questionCount}][type]" class="form-control question-type" data-question-index="${questionCount}" required>
                                <option value="true_false">{{ __('messages.True/False') }}</option>
                                <option value="multiple_choice">{{ __('messages.Multiple Choice') }}</option>
                            </select>
                        </div>
                        <div class="multiple-choice-options d-none" id="multiple-choice-options-${questionCount}">
                            <div class="form-group">
                                <label>{{ __('messages.Option 1') }}</label>
                                <input type="text" name="questions[${questionCount}][option_1]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{ __('messages.Option 2') }}</label>
                                <input type="text" name="questions[${questionCount}][option_2]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{ __('messages.Option 3') }}</label>
                                <input type="text" name="questions[${questionCount}][option_3]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{ __('messages.Option 4') }}</label>
                                <input type="text" name="questions[${questionCount}][option_4]" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('messages.Correct Answer') }}</label>
                            <input type="text" name="questions[${questionCount}][correct_answer]" class="form-control" required>
                        </div>
                        <button type="button" class="btn btn-danger remove-question">{{ __('messages.Remove Question') }}</button>
                    </div>
                `;
                document.getElementById("questions-container").insertAdjacentHTML("beforeend", questionBlock);
            });

            // Toggle multiple-choice options visibility based on question type
            document.getElementById("questions-container").addEventListener("change", function (e) {
                if (e.target.classList.contains("question-type")) {
                    const questionIndex = e.target.getAttribute("data-question-index");
                    const multipleChoiceBlock = document.getElementById(`multiple-choice-options-${questionIndex}`);
                    if (e.target.value === "multiple_choice") {
                        multipleChoiceBlock.classList.remove("d-none");
                    } else {
                        multipleChoiceBlock.classList.add("d-none");
                    }
                }
            });

            // Remove question block
            document.getElementById("questions-container").addEventListener("click", function (e) {
                if (e.target.classList.contains("remove-question")) {
                    e.target.closest(".question-block").remove();
                }
            });
        });
    </script>
@endsection
