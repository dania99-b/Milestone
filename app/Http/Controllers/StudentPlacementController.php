<?php

namespace App\Http\Controllers;

use App\Models\StudentQuestionList;
use App\Models\Test;
use App\Models\Student;
use App\Models\Question;
use App\Models\Question_List;
use Illuminate\Http\Request;

class StudentPlacementController extends Controller
{
    public function storeStudentAnswers(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'test_id' => 'required|exists:tests,id',
            'answers' => 'required|array',
            'answers.*.question_list_id' => 'required',
            'answers.*.answer_id' => 'required',
        ]);

        $student = Student::findOrFail($validatedData['student_id']);
        $test = Test::findOrFail($validatedData['test_id']);
if($student&& $test){
        foreach ($validatedData['answers'] as $answerData) {
            $questionList = Question_List::findOrFail($answerData['question_list_id']);

            $studentAnswer = new StudentQuestionList();
            $studentAnswer->answer_id = $answerData['answer_id'];
            $studentAnswer->question_list()->associate($questionList);
            $student->questions()->save($studentAnswer);
        }

        return response()->json(['message' => 'Answers stored successfully']);
    }}}

