<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher_Schedule extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'teacher_id',
        'day',
        'start_time',
        'end_time'
        ];
    public $timestamps = true;
    use HasFactory;
    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
   
}
