<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRate extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'student_id',
        'teacher_id',
        'rate',
        'created_at',
        'updated_at',
        'note'
        ];

        public function student(){
            $this->belongsTo(Student::class);
        }
        public function teacher(){
            $this->belongsTo(Teacher::class);
        }
}
