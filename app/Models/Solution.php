<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'class_id',
        'period_id',
        'teacher_id',
        'course_name_id',
        'day_id'
        ];

        public function course(){
            return $this->belongsTo(CourseName::class);
        }

        public function teacher(){
            return $this->belongsTo(Teacher::class);
        }
        public function day(){
            return $this->belongsTo(Day::class);
        }

        public function period(){
            return $this->belongsTo(Period::class);
        }

        public function class(){
            return $this->belongsTo(Period::class);
        }
}
