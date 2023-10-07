<?php

namespace App\Models;

use Illuminate\Support\Str;
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

    public function getDatingAttribute()
    {
        $separator = ' – ';
        $years_from = self::formatDatesIntoCollection($this->year_from);
        $years_to = self::formatDatesIntoCollection($this->year_to)->sort();
        $acquisition_dates = self::formatDatesIntoCollection($this->acquisition_date);
        $year_from = $years_from->first();
        $year_to = $years_to->first();
        if (Str::contains($year_from, 'Chr.')) {
            if (Str::contains($year_to, 'Chr.') && $year_from != $year_to && $year_to != ' v. Chr.') {
                return $year_from . $separator . $year_to;
            }
            return $year_from;
        }
        if (!empty($year_to)) {
            return $year_from . $separator . $year_to;
        } else {
            if ($year_from != $acquisition_dates->first) {
                return $years_from->first();
            }
        }
        return null;
    }

    public function getSimilar($limit = 1, $exclude = [])
    {
        $items = $this->has('assets')->where('collection', 'LIKE', $this->collection)->where('id', '>', $this->id)->orderBy('id', 'asc')->whereNotIn('id', $exclude)->limit($limit)->get();
        if ($items->count() == 0) {
            $items = $this->has('assets')->where('collection', 'LIKE', $this->collection)->where('id', '<', $this->id)->orderBy('id', 'desc')->whereNotIn('id', $exclude)->limit($limit)->get();
        }
        if ($items->count() == 0) {
            $items = $this->has('assets')->whereNotIn('id', $exclude)->inRandomOrder()->limit($limit)->get();
        }
        return $items;
    }

    public static function formatDatesIntoCollection($date_string, $separator = ' / ')
    {
        return collect(explode($separator, $date_string))->map(function ($year) {
            return  preg_replace('/^.*?(\d{4})$/', '$1', $year);
        });
    }
}
