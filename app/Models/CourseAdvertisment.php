<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAdvertisment extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'course_id',
        'is_shown',
        'published_at',
        'expired_at'
    ];
}
