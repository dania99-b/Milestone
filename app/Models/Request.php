<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'student_id',
        'reservation_id',
        'date'
       
        ];

        public function reservation(){
            return $this->hasOne(Reservation::class);
        }
        ////////////////////////////
}
