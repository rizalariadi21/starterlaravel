<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    public $timestamps = false;

    protected $fillable = [
        'pengguna', 'username', 'password', 'level',
    ];

    protected $hidden = [
        'password',
    ];
}