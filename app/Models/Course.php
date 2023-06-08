<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\MockObject\Stub;

class Course extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "courses";
    protected $fillable=[
        'id',
        'class_id',
        'course_ename',
        'start_hour',
        'end_hour',
        'start_day',
        'end_day',
        'status',
        'qr_code',
        'created_at',
        'days',
        'course_name_id',
        'teacher_id'
        ];
        
        public function class(){
			return $this->belongsTo(Classs::class);
		}
        public function student(){
            return $this->belongsToMany(Student::class,'attendences')->withPivot('student_id','course_id','is_absent','created_at');
        }
      
        public function courseName()
        {
            return $this->belongsTo(CourseName::class);
        }

        public function reservation(){
            return $this->hasMany(Reservation::class);
    }
    public function homeworks(){
        return $this->hasMany(Homework::class);
    }
    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
    public function course_result(){
        return $this->hasMany(CourseResult::class);
    }
    public function period(){
        return $this->belongsTo(Period::class);
    }
    
}