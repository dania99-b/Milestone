<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'id',
        'name'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
