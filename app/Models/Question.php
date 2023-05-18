<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable=[
        'id',
        'text',
        'level',
        'type_id',
        'mark'
        ];

    public function answers(){
        return $this->hasMany(Answer::class);
    }

    public function questionType(){
        return $this->belongsTo(QuestionType::class);
    }

    public function tests(){
        return $this->belongsToMany(Test::class,'question_lists');
    }
}
