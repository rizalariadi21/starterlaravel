<?php

namespace App\Observers;

use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        AuditLogger::record($model, 'create');
    }

    public function updated(Model $model): void
    {
        AuditLogger::record($model, 'update');
    }

    public function deleted(Model $model): void
    {
        AuditLogger::record($model, 'delete');
    }
}