<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AuditLog;

class AuditController extends Controller
{
    public function auditLogs(Request $request)
    {
        $length = (int) ($request->input('length', 10));
        $start  = (int) ($request->input('start', 0));
        $draw   = (int) ($request->input('draw', 0));
        $search = trim((string) $request->input('search.value', ''));

        $base = AuditLog::query()
            ->leftJoin('pengguna', 'pengguna.id_pengguna', '=', 'audit_logs.actor_id')
            ->select('audit_logs.*', 'pengguna.pengguna as actor_name', 'pengguna.username as actor_username');

        $recordsTotal = (int) (clone $base)->count();

        if ($search !== '') {
            $base->where(function($q) use ($search) {
                $q->where('audit_logs.table', 'like', "%$search%")
                  ->orWhere('audit_logs.action', 'like', "%$search%")
                  ->orWhere('audit_logs.primary_key', 'like', "%$search%")
                  ->orWhere('pengguna.pengguna', 'like', "%$search%")
                  ->orWhere('pengguna.username', 'like', "%$search%")
                  ->orWhere('audit_logs.url', 'like', "%$search%");
            });
        }

        $recordsFiltered = (int) (clone $base)->count();

        $logs = $base->orderBy('audit_logs.created_at', 'desc')
                     ->skip($start)->take($length)->get();

        $data = [];
        foreach ($logs as $log) {
            $actor = $log->actor_name ? ($log->actor_username ? ($log->actor_name . ' (' . $log->actor_username . ')') : $log->actor_name) : '-';
            $data[] = [
                (string) optional($log->created_at)->format('Y-m-d H:i:s'),
                (string) $log->table,
                (string) $log->action,
                (string) $log->primary_key,
                (string) $actor,
                (string) ($log->ip ?? '-'),
                (string) ($log->url ?? ''),
                is_string($log->changes_before) ? (string) $log->changes_before : json_encode($log->changes_before),
                is_string($log->changes_after) ? (string) $log->changes_after : json_encode($log->changes_after),
            ];
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function loginLogs(Request $request)
    {
        $length = (int) ($request->input('length', 10));
        $start  = (int) ($request->input('start', 0));
        $draw   = (int) ($request->input('draw', 0));
        $search = trim((string) $request->input('search.value', ''));

        $base = DB::table('login_logs')->select('id','username','user_id','ip','success','message','user_agent','created_at');

        $recordsTotal = (int) (clone $base)->count();

        if ($search !== '') {
            $base->where(function($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                  ->orWhere('ip', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%")
                  ->orWhere('user_agent', 'like', "%$search%")
                  ->orWhere('user_id', 'like', "%$search%")
                  ->orWhere('success', 'like', "%$search%");
            });
        }

        $recordsFiltered = (int) (clone $base)->count();

        $logs = $base->orderBy('created_at', 'desc')
                     ->skip($start)->take($length)->get();

        $data = [];
        foreach ($logs as $log) {
            $data[] = [
                (string) (isset($log->created_at) ? date('Y-m-d H:i:s', strtotime($log->created_at)) : ''),
                (string) ($log->username ?? ''),
                (string) ($log->user_id ?? ''),
                (string) ($log->ip ?? ''),
                (int) ($log->success ? 1 : 0),
                (string) ($log->message ?? ''),
                (string) ($log->user_agent ?? ''),
            ];
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
