<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = null;
        $auth = $request->header('Authorization');
        if ($auth && stripos($auth, 'Bearer ') === 0) {
            $token = trim(substr($auth, 7));
        }
        if (!$token) {
            $token = $request->input('authenticityToken') ?: $request->query('authenticityToken');
        }
        if (!$token) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Missing token'], 401);
        }
        $user = Pengguna::where('api_token', $token)->where('status', 1)->first();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Invalid token'], 401);
        }
        Auth::setUser($user);
        return $next($request);
    }
}