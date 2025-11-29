<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class MenuService
{
    public static function forContext(string $context): ?array
    {
        $menu = Config::get('menus.' . $context);
        return is_array($menu) ? $menu : null;
    }
}
