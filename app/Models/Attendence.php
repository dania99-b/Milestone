<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory;
    protected $table = "attendences";
    public $timestamps = ["created_at"]; //only want to used created_at column
    const UPDATED_AT = null;
    protected $fillable=[
        'id',
        'student_id',
        'course_id',
        'created_at',
   
        ];
}
