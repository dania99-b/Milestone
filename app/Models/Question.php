<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'text',
        'level',
        'type_id'
        ];
    public $timestamps = true;
    public function answers(){
        $this->hasMany(Answer::class);
    }
}
