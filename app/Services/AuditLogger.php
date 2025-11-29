<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class AuditLogger
{
    protected static array $hiddenKeys = [
        'password',
        'remember_token',
        'token',
        'api_token',
        'secret',
        'client_secret',
    ];

    protected static function filter(array $data): array
    {
        foreach (self::$hiddenKeys as $key) {
            if (array_key_exists($key, $data)) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    public static function record(EloquentModel $model, string $action): void
    {
        try {
            if (method_exists($model, 'getTable') && $model->getTable() === 'audit_logs') {
                return;
            }
            if ($model instanceof \App\Models\AuditLog) {
                return;
            }
            $table = $model->getTable();
            $pkName = $model->getKeyName();
            $pk = (string) $model->getKey();

            $before = [];
            $after = [];

            if ($action === 'create') {
                $after = $model->getAttributes();
            } elseif ($action === 'update') {
                $changes = $model->getChanges();
                $after = array_intersect_key($model->getAttributes(), $changes);
                $before = array_intersect_key($model->getOriginal(), $changes);
                if (empty($after) && empty($before)) {
                    $after = $model->getAttributes();
                    $before = null;
                }
            } elseif ($action === 'delete') {
                $before = $model->getOriginal();
            }

            $before = self::filter($before);
            $after = self::filter($after);

            $actorId = auth()->check() ? auth()->id() : null;
            $req = request();
            $ip = $req ? $req->ip() : null;
            $ua = $req ? ($req->header('User-Agent') ?? null) : null;
            $url = $req ? $req->fullUrl() : null;

            AuditLog::create([
                'table' => $table,
                'model' => get_class($model),
                'primary_key' => $pkName . ':' . $pk,
                'action' => $action,
                'changes_before' => $before ?: null,
                'changes_after' => $after ?: null,
                'actor_id' => $actorId,
                'actor_type' => 'user',
                'ip' => $ip,
                'user_agent' => $ua,
                'url' => $url,
            ]);
        } catch (\Throwable $e) {
            // do not break main request if logging fails
        }
    }
}
