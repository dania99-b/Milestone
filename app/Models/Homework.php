<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'text',
        'file',
        'course_id'
        ];
        public function course(){
			return $this->belongsTo(Course::class);
		}

}
