<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckLevel
{
    public function handle($request, Closure $next, ...$levels)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }
        $level = (int) ($user->level ?? 0);
        $allowed = array_map('intval', $levels);
        if (!in_array($level, $allowed, true)) {
            abort(403);
        }
        return $next($request);
    }
}
