<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $length = (int) ($request->input('length', 10));
        $start  = (int) ($request->input('start', 0));
        $draw   = (int) ($request->input('draw', 0));
        $search = trim((string) $request->input('search.value', ''));

        $base = Pengguna::query()->select('id_pengguna','pengguna','institusi','no_hp','username','level','status','created_at','updated_at');
        $recordsTotal = (int) $base->count();

        if ($search !== '') {
            $base->where(function($q) use ($search) {
                $q->where('pengguna', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%");
            });
        }

        $recordsFiltered = (int) $base->count();

        $rows = $base->orderBy('id_pengguna', 'asc')
                     ->skip($start)->take($length)->get();

        $data = [];
        foreach ($rows as $r) {
            $data[] = [
                (int) $r->id_pengguna,
                (string) $r->pengguna,
                (string) ($r->institusi ?? ''),
                (string) ($r->no_hp ?? ''),
                (string) $r->username,
                (int) $r->level,
                (int) $r->status,
                (string) optional($r->created_at)->format('Y-m-d H:i:s'),
                (string) optional($r->updated_at)->format('Y-m-d H:i:s'),
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
