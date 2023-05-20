<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDay extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'day_id',
        'course_id'
        ];
}
