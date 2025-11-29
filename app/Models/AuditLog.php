<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'table',
        'model',
        'primary_key',
        'action',
        'changes_before',
        'changes_after',
        'actor_id',
        'actor_type',
        'ip',
        'user_agent',
        'url',
    ];

    protected $casts = [
        'changes_before' => 'array',
        'changes_after' => 'array',
        'actor_id' => 'integer',
    ];
}

