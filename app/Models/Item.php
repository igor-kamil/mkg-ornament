<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'image_src',
    ];

}
