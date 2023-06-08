<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'employee_id',
        'experince_years',
        'period_id'
        ];
    public $timestamps = true;

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    public function course(){
        return $this->hasMany(Course::class);
    }

    public function rate(){
        return $this->hasMany(StudentRate::class);
    }
    public function periods(){
        return $this->belongsToMany(Period::class,'teacher_periods')->withPivot('teacher_id','period_id','is_occupied');
    }
    public function solution(){
        return $this->hasMany(Solution::class);
    }
}
