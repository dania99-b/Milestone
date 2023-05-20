<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "attendences";
    protected $fillable=[
        'id',
        'student_id',
        'course_id',
        'created_at',
   
        ];
}
