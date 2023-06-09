<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationFile extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'course_id',
        'file_types_id',
        'file'
        ];
        public function types(){
           return $this->belongsTo(FileTypes::class,'id');
        }

        public function course(){
          return  $this->belongsTo(CourseName::class);
        }
}
