<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherPeriod extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable=[
        'id',
        'teacher_id',
        'period_id',
        'is_occupied'
        ];

}
