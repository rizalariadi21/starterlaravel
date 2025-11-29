<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    protected $table = 'menu_permissions';

    protected $fillable = [
        'route_name',
        'allowed_levels',
    ];

    protected $casts = [
        'allowed_levels' => 'array',
    ];
}