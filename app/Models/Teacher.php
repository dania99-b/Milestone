<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable=[
        'id',
        'employee_id'
        ];
    public $timestamps = false;
    use HasFactory;
    public function user(){
       return $this->belongsTo(User::class);
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    public function rate(){
        return $this->hasMany(StudentRate::class);
    }
    public function teacher_schedule(){
        return $this->hasMany(Teacher_Schedule::class);
    }
}
