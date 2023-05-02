<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\GuestQuestionList;
use App\Models\Question;
use App\Models\Question_List;
use Illuminate\Http\Request;

class ResultConrtoller extends Controller
{
   public function calcResult(Request $request){
   $l='true';
   $guest=$request['guest_id'];
   $testreq=$request['test_id'];
   $question_list=Question_List::where('test_id',$testreq)->get();
   print($question_list);
   $question_list_id=$question_list->pluck('id');
   for($i=0;$i<count($question_list_id);$i++){
   $guest_answer=GuestQuestionList::where('question_list_id',$question_list_id[$i])->get()->pluck('answer_id');
   print('guest_answer');
   print($guest_answer);
   for($j=0;$j<count($guest_answer);$j++){
      $original_answer=Answer::where('id',$guest_answer[$j])->get()->pluck('is_true');
      print($original_answer);
      if( $original_answer=='true')
      print('yessssss');
      else print('nooooo');
   }
   //get the all question of this test 
   /*$guest_question_list=GuestQuestionList::where('guest_id',$guest)->get()->pluck('question_list_id');
   foreach($guest_question_list as $lists){
   $question_list=Question_List::where('id',$lists)->where('test_id',$testreq)->pluck('question_id');
   foreach($question_list as $questions){

      $question_list_answers=Answer::where('question_id',$questions)->where('is_true','true')->get()->pluck('id');
      print($question_list_answers);
      $guest_answer=GuestQuestionList::where('guest_id',$guest)->get()->pluck('answer_id');
     for($i=0;$i<count($guest_answer);$i++){
      print($guest_answer[$i]);
     // print($questions);
      if($guest_answer[$i]==$questions&&Answer::where('question_id',$questions)->get()->pluck('is_true')=='true')
print('yesss');*/
     }
   }
   
   
//get all answers of all this questions

 //  }
 //  return $test;

   }
//}
