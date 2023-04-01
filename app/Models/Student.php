<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable=[
        'id',
        'user_id',
        'image'
        ];
    public $timestamps = false;
    use HasFactory;
    public function user(){
        $this->belongsTo(User::class);
    }
    public function course(){
        return $this->belongsToMany(Course::class,'attendences')->withPivot('student_id','course_id','is_absent','created_at');
    }
}
