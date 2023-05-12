<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertismentType extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'name',
        ];

    public function advertisment(){
        $this->hasMany(Advertisment::class);
    }
}
