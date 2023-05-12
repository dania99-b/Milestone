<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'image',
        'user_id',
        'country_id'
        ];
        
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function course(){
        return $this->belongsToMany(Course::class,'attendences')->withPivot('student_id','course_id','is_absent','created_at');
    }

    public function rate(){
        $this->hasMany(StudentRate::class);
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }

    // public function questions()
    // {
	// 	return $this->hasMany(StudentQuestionList::class);
    // }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
