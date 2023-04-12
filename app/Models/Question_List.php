<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question_List extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='question_lists';
    protected $fillable=[
        'id',
        'test_id',
        'question_id',

        ];
        
        public function questions()
        {
            return $this->belongsToMany(Question::class, 'question_lists');
        }
    
        public function guests()
        {
            return $this->belongsToMany(Guest::class, 'guest_question_lists')->withPivot('answer');;
        }
        public function guest_question_lists()
{
    return $this->hasMany(GuestQuestionList::class, 'question_list_id');
}
    }

   