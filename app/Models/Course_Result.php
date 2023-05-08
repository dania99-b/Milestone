<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_Result extends Model
{
    use HasFactory;
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
    public $timestamps = true;
    public function course(){
        return $this->belongsTo(Course::class);
    }
}
