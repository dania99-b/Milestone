<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;
    protected $fillable=[
        'start_hour',
        'end_hour',
        'is_available'];

public function teacher(){
    return $this->belongsTo(Teacher::class,'teacher_periods')->withPivot('teacher_id','period_id','is_occupied');
}

public function classes(){
    return $this->hasMany(Classs::class,'class_periods')->withPivot('class_id','period_id','is_occupied');
}
public function course(){
    return $this->hasMany(Course::class);
}
public function solution(){
    return $this->hasMany(Solution::class);
}


}
