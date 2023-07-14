<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertismentType extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'name',
        'shown_for'
        ];

    public function advertisment(){
        $this->hasMany(Advertisment::class);
    }
}
