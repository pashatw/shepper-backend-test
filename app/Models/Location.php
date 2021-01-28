<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	protected $table = "location";
	
    protected $fillable = [
        'title',
        'label',
        'latitude',
        'longitude',
        'radius',
    ];

    protected $casts = [
        'latitude'  => 'int',
        'longitude' => 'int',
        'radius'    => 'float',
    ];
}
