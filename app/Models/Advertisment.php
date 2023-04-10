<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisment extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'title',
        'image',
        'advertisment_type_id',
        'is_shown',
        'description'
   
        ];
        public function advertismentType(){
            $this->belongsTo(AdvertismentType::class);
        }
        public function cvs(){
            $this->hasMany(CV::class);
        }
}
