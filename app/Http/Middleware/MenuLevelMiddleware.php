<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\MenuPermission;

class MenuLevelMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }
        $route = $request->route();
        $name = $route ? $route->getName() : null;
        if ($name) {
            $levels = $this->findLevelsOverride($name);
            if ($levels === null) {
                $levels = $this->findLevelsForRoute($name);
            }
            if (is_array($levels) && count($levels) > 0) {
                $level = (int) ($user->level ?? 0);
                $allowed = array_map('intval', $levels);
                if (!in_array($level, $allowed, true)) {
                    abort(403);
                }
            }
        }
        return $next($request);
    }

    protected function findLevelsOverride(string $routeName)
    {
        try {
            $perm = MenuPermission::where('route_name', $routeName)->first();
            if ($perm && is_array($perm->allowed_levels) && count($perm->allowed_levels) > 0) {
                return $perm->allowed_levels;
            }
        } catch (\Throwable $e) {
            // ignore db errors
        }
        return null;
    }

    protected function findLevelsForRoute(string $routeName)
    {
        $menus = config('menus.global');
        if (!$menus || !is_array($menus)) {
            $menus = config('sidebar.menu');
        }
        if (!is_array($menus)) {
            return null;
        }
        $stack = $menus;
        while ($stack) {
            $item = array_shift($stack);
            if (!is_array($item)) continue;
            if (!empty($item['route-name']) && $item['route-name'] === $routeName) {
                if (!empty($item['levels'])) return $item['levels'];
                if (!empty($item['roles'])) {
                    $levels = [];
                    foreach ((array) $item['roles'] as $r) {
                        if ($r === 'admin') $levels[] = 1;
                        elseif ($r === 'user') $levels[] = 2;
                        elseif ($r === 'viewer') $levels[] = 3;
                    }
                    return $levels ?: null;
                }
                return null;
            }
            if (!empty($item['sub_menu']) && is_array($item['sub_menu'])) {
                foreach ($item['sub_menu'] as $sub) {
                    $stack[] = $sub;
                }
            }
        }
        return null;
    }
}