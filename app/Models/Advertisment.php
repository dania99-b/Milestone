<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisment extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'title',
        'image',
        'advertisment_type_id',
        'tips',
        'is_shown',
        'description',
        'published_at',
        'expired_at',
        'course_id'
        ];

        public function advertismentType(){
           return $this->belongsTo(AdvertismentType::class);
        }
        
        public function cvs(){
            return $this->hasMany(CV::class);
        }
        public function course(){
            return $this->belongsTo(Course::class);
        }
}