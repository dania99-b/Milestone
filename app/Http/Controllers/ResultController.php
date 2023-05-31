<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultRequest;
use App\Models\CourseResult;
use App\Models\Mark;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function uploadStudentResult(ResultRequest $request)
    {
        $validatedData = $request->validated();
        $studentId = $validatedData['student_id'];
        
        $courseResult=CourseResult::where('student_id', $studentId)
        ->latest('id')
        ->first();
        
        if ($courseResult) {
            Mark::firstOrCreate([
                'med' => $validatedData['med'],
                'presentation' => $validatedData['presentation'],
                'oral' => $validatedData['oral'],
                'final' => $validatedData['final'],
                'homework' => $validatedData['homework'],
                'course_result_id' => $courseResult->id
            ]);
            
         
            $total = $validatedData['oral'] + $validatedData['presentation'] + $validatedData['homework'] +
                $validatedData['med'] + $validatedData['final'];
            
           
            $status = ($total < 60) ? 'Failed' : 'Passed';
            
           
            $courseResult->update([
                'total' => $total,
                'status' => $status
            ]);
            
            
            
            return response()->json([
                'message' => 'Student result uploaded successfully',
                'data' => $courseResult
            ]);
        } else {
            return response()->json([
                'message' => 'No course result found for the specified student ID'
            ], 404);
        }
    
}}
