<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRate extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable=[
        'id',
        'student_id',
        'teacher_id',
        'rate'
        ];

        public function student(){
            $this->belongsTo(Student::class);
        }
        public function teacher(){
            $this->belongsTo(Teacher::class);
        }
}
