<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPlacement extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
		'student_id',
		'test_id',
		'mark',
        'level',
        ];
}
