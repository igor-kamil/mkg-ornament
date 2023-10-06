<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'image_src',
        'asset_id',
    ];

    public function assets()
    {
        return $this->hasMany(ItemAsset::class);
    }

    public function previewAsset()
    {
        return $this->hasOne(ItemAsset::class)->orderBy('name');
    }

    public function getSimilar($limit = 1, $exclude = [])  {
        $items = $this->has('assets')->where('collection', 'LIKE', $this->collection)->where('id', '>', $this->id)->orderBy('id', 'asc')->whereNotIn('id', $exclude)->limit($limit)->get();
        if ($items->count() == 0) {
            $items = $this->has('assets')->where('collection', 'LIKE', $this->collection)->where('id', '<', $this->id)->orderBy('id', 'desc')->whereNotIn('id', $exclude)->limit($limit)->get();
        }
        if ($items->count() == 0) {
            $items = $this->has('assets')->whereNotIn('id', $exclude)->inRandomOrder()->limit($limit)->get();
        }
        return $items;
    }

}
