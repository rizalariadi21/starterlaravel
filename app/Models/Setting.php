<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['key','value'];

    public static function get(string $key, $default = null)
    {
        $cacheKey = 'setting:' . $key;
        return Cache::remember($cacheKey, 300, function() use ($key, $default) {
            $row = static::where('key', $key)->first();
            return $row ? $row->value : $default;
        });
    }
}

