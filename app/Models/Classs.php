<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classs extends Model
{
    use HasFactory;
    protected $table = "classes";
    public $timestamps = false;
    protected $fillable=[
        'id',
        'name',
        'max_num',
        'status',
    ];
        public function courses(){
			$this->hasMany(Course::class);
		}      
        public function class_schedule(){
            return $this->hasMany(Class_Schedule::class);
        }
}
