<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    public $timestamps = true;

    protected $fillable = [
        'pengguna', 'institusi', 'no_hp', 'username', 'password', 'level', 'status', 'api_token',
    ];

    protected $hidden = [
        'password',
    ];

    public function getRouteKeyName()
    {
        return 'id_pengguna';
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'integer',
    ];
}
