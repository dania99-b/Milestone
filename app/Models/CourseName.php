<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_Name extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'course_name'
        ];

        public function course(){
            return $this->belongsTo(Course::class);
        }

}
