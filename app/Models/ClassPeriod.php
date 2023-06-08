<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassPeriod extends Model
{
    use HasFactory;
    protected $table = "class_periods";
    public $timestamps = false;
    protected $fillable=[
        'id',
        'class_id',
        'period_id',
        'is_occupied',
        ];
        
}
