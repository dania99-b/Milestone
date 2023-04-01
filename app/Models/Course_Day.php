<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_Day extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'day_id',
        'course_id'
        ];
}
