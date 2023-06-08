<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    public $timestamps = true ;
    protected $fillable=[
        'id',
        'name',
        'is_vacation'
        ];
        
        public function courses(){
            return $this->belongsToMany(Course::class,'course_days')->withPivot('course_id','day_id');
        }
        public function solution(){
            return $this->hasMany(Solution::class);
        }
}
