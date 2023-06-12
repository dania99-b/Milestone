<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'who_we_are',
        'contact_us',
        'services',
        'email'
        ];
}
