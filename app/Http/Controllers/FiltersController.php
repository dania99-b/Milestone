<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Http\Request;

class FiltersController extends Controller
{
   public function getQuestionByType($type){
   $type=QuestionType::where('name',$type)->pluck('id')->first();
   $questions=Question::where('type_id',$type)->get();
   return response()->json($questions,200);

   }
}
