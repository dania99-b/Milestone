<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'image',
        'published_at',
        'expired_at',
        'is_show'
        ];
}
