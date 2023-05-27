<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseName extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'name'
        ];

    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function education_files(){
        return  $this->hasMany(EducationFile::class);
      }

}
