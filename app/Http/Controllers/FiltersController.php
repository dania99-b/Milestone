<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Http\Request;

class FiltersController extends Controller
{
   public function getQuestionByType($typeID){
   $questions=Question::where('type_id',$typeID)->get();
   return response()->json($questions,200);

   }
}
