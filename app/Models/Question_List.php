<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question_List extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable=[
        'id',
        'test_id',
        'question_id',

        ];

   
}
