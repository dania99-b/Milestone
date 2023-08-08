<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fcmToken extends Model
{


   
    use HasFactory;
    protected $table='fcm_tokens';
    public $timestamps = true;
    protected $fillable=[
        'id',
        'course_id',
        'user_id',
        'fcm_token'
        ];

        public function user(){
            return $this->belongsTo(User::class);
          }


}
