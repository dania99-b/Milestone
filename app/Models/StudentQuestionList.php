<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQuestionList extends Model
{
    use HasFactory;
    protected $table ='student_question_lists';
    protected $fillable=[
        'id',
		'student_id',
		'question_list_id',
		'answer',
		
        ];
        public function question_list()
        {
            return $this->belongsTo(Question_List::class, 'question_list_id');
        }

        public function answer()
        {
            return $this->hasOne(Answer::class);
        }
        public function student()
        {
            return $this->belongsTo(Student::class);
        }
}
