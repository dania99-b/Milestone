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
        'type_id'
        ];
   
    public function answers(){
        $this->hasMany(Answer::class);
    }
    public function type(){
        $this->belongsTo(Type::class);
    }
    public function tests()
    {
        return $this->belongsToMany(Test::class,'question_lists');
    }
}
