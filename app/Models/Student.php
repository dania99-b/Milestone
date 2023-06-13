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
        
    public $timestamps = true;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function course(){
        return $this->belongsToMany(Course::class,'attendences')->withPivot('student_id','course_id','is_absent','created_at');
    }
    public function tests()
    {
        return $this->belongsToMany(Test::class,'student_placement_tests')->withPivot('mark', 'level');
    }

    public function rate(){
        $this->hasMany(StudentRate::class);
    }

    public function reservation(){
        return $this->hasMany(Reservation::class);
    }

    // public function questions()
    // {
	// 	return $this->hasMany(StudentQuestionList::class);
    // }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function course_result(){
        return $this->hasMany(CourseResult::class);
    }
    public function attendence(){
        $this->hasMany(Attendence::class);
    }
}
