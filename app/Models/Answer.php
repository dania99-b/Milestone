<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable=[
        'id',
        'name',
        'question_id',
        'is_true'
        ];

        public function question(){
            $this->belongsTo(Question::class);
        }
}
