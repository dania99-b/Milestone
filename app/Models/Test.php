<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable=[
        'id',
        'start_date',
        'end_date',
  
        ];

    public function questions()
    {
        return $this->belongsToMany(Question::class,'question_lists');
    }
}
