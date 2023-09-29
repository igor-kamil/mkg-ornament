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

    public function assets()
    {
        return $this->hasMany(ItemAsset::class);
    }

    public function previewAsset()
    {
        return $this->hasOne(ItemAsset::class)->orderBy('name');
    }

}
