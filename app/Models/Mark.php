<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
            'med',
           'presentation',
          'oral',
          'final',
          'homework',
          'course_result_id'];
          public function courseResult()
          {
              return $this->belongsTo(CourseResult::class);
          }

}
