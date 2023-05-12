<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPlacement extends Model
{
    use HasFactory;
    protected $table="student_placement_tests";
    public $timestamps = true;
    protected $fillable=[
        'id',
		'student_id',
		'test_id',
		'mark',
        'level',
        'created_at'
        ];
}
