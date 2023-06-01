<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseResult extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'student_id',
        'course_id',
        'mark_section_id',
        'created_at',
        'updated_at',
        'total',
        'days_attend',
        'status'
        ];
    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function student(){
        return $this->belongsTo(Student::class);
    }
    public function mark()
    {
        return $this->hasOne(Mark::class);
    }
}
