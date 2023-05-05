<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Class_Schedule extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'class_id',
        'day',
        'start_time',
        'end_time'
        ];
    public $timestamps = true;
    use HasFactory;
    public function class(){
        return $this->belongsTo(Classs::class);
    }
   
}
