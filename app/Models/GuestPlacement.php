<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestPlacement extends Model
{
    use HasFactory;
    protected  $table='guest_placement_tests';
    public $timestamps = true;
    protected $fillable=[
        'id',
		'guest_id',
		'test_id',
		'mark',
        'level',
        ];
}