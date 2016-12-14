<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hauler extends Model
{
    protected $table = 'haulers';

    public $fillable = [
        'name', 'city_id', 'svc_recycle', 'svc_waste'
    ];

    public $timestamps = true;

}
