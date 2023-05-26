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
        ];
        
        public function class(){
			$this->belongsTo(Classs::class);
		}
        public function student(){
            return $this->belongsToMany(Student::class,'attendences')->withPivot('student_id','course_id','is_absent','created_at');
        }
      
        public function name(){
            return $this->hasOne(CourseName::class);
        }
        public function reservation(){
            return $this->hasMany(Reservation::class);
    }
}