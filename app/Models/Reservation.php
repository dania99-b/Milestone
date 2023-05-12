<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'student_id',
        'course_id',
        'status',
        ];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
