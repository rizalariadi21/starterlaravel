<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'menu_items';
    protected $fillable = [
        'parent_id','title','icon','url','route_name','caret','levels','sort_order'
    ];
    protected $casts = [
        'caret' => 'boolean',
    ];

    public static function getTree()
    {
        $items = self::orderBy('parent_id')->orderBy('sort_order')->get();
        $byParent = [];
        foreach ($items as $it) {
            $byParent[$it->parent_id ?: 0][] = $it;
        }
        $build = function($parentId) use (&$build, $byParent) {
            $arr = [];
            foreach (($byParent[$parentId ?: 0] ?? []) as $it) {
                $node = [
                    'icon' => $it->icon,
                    'title' => $it->title,
                    'url' => $it->url,
                    'route-name' => $it->route_name,
                    'levels' => self::decodeLevels($it->levels),
                    'caret' => (bool) $it->caret,
                ];
                $children = $build($it->id);
                if (!empty($children)) { $node['sub_menu'] = $children; }
                $arr[] = $node;
            }
            return $arr;
        };
        return $build(null);
    }

    protected static function decodeLevels($levels)
    {
        if (!$levels) return null;
        $decoded = null;
        try { $decoded = json_decode($levels, true); } catch (\Throwable $e) {}
        return is_array($decoded) ? $decoded : null;
    }

    public static function buildMenuArrayWithFallback()
    {
        if (self::query()->count() === 0) {
            self::importFromConfig();
        }
        $tree = self::getTree();
        if (empty($tree)) {
            return config('menus.global') ?: config('sidebar.menu');
        }
        return $tree;
    }

    public static function importFromConfig()
    {
        $source = config('menus.global') ?: config('sidebar.menu');
        if (!$source || !is_array($source)) return;
        $order = 0;
        $insertRec = function($item, $parentId = null) use (&$insertRec, &$order) {
            $rec = self::create([
                'parent_id' => $parentId,
                'title' => $item['title'] ?? '',
                'icon' => $item['icon'] ?? null,
                'url' => $item['url'] ?? 'javascript:;',
                'route_name' => $item['route-name'] ?? null,
                'caret' => !empty($item['caret']),
                'levels' => isset($item['levels']) ? json_encode($item['levels']) : null,
                'sort_order' => $order++,
            ]);
            if (!empty($item['sub_menu']) && is_array($item['sub_menu'])) {
                foreach ($item['sub_menu'] as $sm) {
                    $insertRec($sm, $rec->id);
                }
            }
        };
        foreach ($source as $it) { $insertRec($it, null); }
    }
}

