<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classs extends Model
{
    use HasFactory;
    protected $table = "classes";
    public $timestamps = true;
    protected $fillable=[
        'id',
        'name',
        'max_num',
        'period_id',
        'status',

    ];
    
    public function courses(){
       return  $this->hasMany(Course::class);
    }
    public function periods(){
        return $this->belongsToMany(Period::class,'class_periods', 'class_id', 'period_id')->withPivot('class_id','period_id','is_occupied');
    }
    public function solution(){
        return $this->hasMany(Solution::class);
    }
}