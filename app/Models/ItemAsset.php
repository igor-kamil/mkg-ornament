<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mdc_id',
        'item_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
