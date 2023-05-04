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
   $mark=0;
   $l='true';
   $guest=$request['guest_id'];
   $testreq=$request['test_id'];
   $question_list=Question_List::where('test_id',$testreq)->get();
   
   $question_list_id=$question_list->pluck('id');
   print('question list');
   print($question_list_id);
   for($i=0;$i<count($question_list_id);$i++){
      $question = Question_List::find($question_list_id[$i]);
      $question_id = $question->question_id;
      $question_mark = Question::find($question_id)->mark;
      $answers=Answer::where('question_id',$question_id)->where('is_true','true')->get()->pluck('id');
      $guest_answer = GuestQuestionList::where('guest_id', $guest)->where('question_list_id', $question_list_id[$i])->get()->pluck('answer_id');
      if( $answers==$guest_answer)
      $mark=$mark+$question_mark;

  }
  print('mark'.$mark);
 
     //if( $original_answer=='true')
  
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
   
   
   
//get all answers of all this questions

 //  }
 //  return $test;

   
