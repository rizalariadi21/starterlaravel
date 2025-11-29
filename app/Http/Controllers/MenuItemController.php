<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MenuItem;

class MenuItemController extends Controller
{
    public function index()
    {
        if (MenuItem::query()->count() === 0) {
            MenuItem::importFromConfig();
        }
        $parentGroup = MenuItem::where('title', 'APLIKASI')->first();
        if (!$parentGroup) {
            $parentGroup = MenuItem::create([
                'parent_id' => null,
                'title' => 'APLIKASI',
                'icon' => 'fa fa-cogs',
                'url' => 'javascript:;',
                'caret' => true,
                'sort_order' => 0,
            ]);
        }
        $menuItemsEntry = MenuItem::where('route_name', 'menu-items-index')->first();
        if (!$menuItemsEntry) {
            MenuItem::create([
                'parent_id' => $parentGroup->id,
                'title' => 'Menu Items',
                'icon' => 'fa fa-list',
                'url' => '/menu-items',
                'route_name' => 'menu-items-index',
                'caret' => false,
                'levels' => json_encode([1]),
                'sort_order' => 999,
            ]);
        }

        $items = MenuItem::orderBy('parent_id')->orderBy('sort_order')->get();
        $nodes = [];
        foreach ($items as $it) {
            $nodes[] = [
                'id' => (string) $it->id,
                'parent' => $it->parent_id ? (string) $it->parent_id : '#',
                'text' => $it->title,
                'data' => [
                    'icon' => $it->icon,
                    'url' => $it->url,
                    'route_name' => $it->route_name,
                    'caret' => (bool) $it->caret,
                    'levels' => self::decodeLevels($it->levels),
                ],
            ];
        }
        $ionIcons = $this->extractIoniconsFromBlade();
        $gradients = $this->extractGradientsFromHelper();
        return view('halaman/menu_items/index', ['nodes' => $nodes, 'icons' => $ionIcons, 'gradients' => $gradients]);
    }

    public function show(MenuItem $menuItem)
    {
        return response()->json([
            'id' => $menuItem->id,
            'parent_id' => $menuItem->parent_id,
            'title' => $menuItem->title,
            'icon' => $menuItem->icon,
            'url' => $menuItem->url,
            'route_name' => $menuItem->route_name,
            'caret' => (bool) $menuItem->caret,
            'levels' => self::decodeLevels($menuItem->levels),
            'sort_order' => $menuItem->sort_order,
        ]);
    }

    public function store(Request $request)
    {
        $parentId = $request->input('parent_id');
        $title = $request->input('title', 'Item Baru');
        $position = (int) $request->input('position', 0);

        $item = null;
        DB::transaction(function () use (&$item, $parentId, $title, $position) {
            $siblings = MenuItem::where('parent_id', $parentId)->orderBy('sort_order')->get();
            foreach ($siblings as $sib) {
                if ($sib->sort_order >= $position) {
                    $sib->sort_order++;
                    $sib->save();
                }
            }
            $item = MenuItem::create([
                'parent_id' => $parentId ?: null,
                'title' => $title,
                'url' => 'javascript:;',
                'caret' => false,
                'sort_order' => $position,
            ]);
        });

        return response()->json([
            'id' => $item->id,
            'node' => [
                'id' => (string) $item->id,
                'parent' => $item->parent_id ? (string) $item->parent_id : '#',
                'text' => $item->title,
                'data' => [
                    'icon' => $item->icon,
                    'url' => $item->url,
                    'route_name' => $item->route_name,
                    'caret' => (bool) $item->caret,
                    'levels' => self::decodeLevels($item->levels),
                ],
            ],
        ], 201);
    }

    public function update(MenuItem $menuItem, Request $request)
    {
        $levelsInput = $request->input('levels');
        $levelsValue = $menuItem->levels;
        if ($request->has('levels')) {
            $arr = [];
            foreach ((array) $levelsInput as $lv) {
                if ($lv === '' || $lv === null) continue;
                $arr[] = (int) $lv;
            }
            $levelsValue = !empty($arr) ? json_encode($arr) : null;
        }
        $menuItem->fill([
            'title' => $request->input('title', $menuItem->title),
            'icon' => $request->input('icon', $menuItem->icon),
            'url' => $request->input('url', $menuItem->url),
            'route_name' => $request->input('route_name', $menuItem->route_name),
            'caret' => $request->boolean('caret', $menuItem->caret),
            'levels' => $levelsValue,
        ]);
        $menuItem->save();
        return response()->json(['ok' => true]);
    }

    public function destroy(MenuItem $menuItem)
    {
        DB::transaction(function () use ($menuItem) {
            $ids = $this->collectDescendantIds($menuItem->id);
            MenuItem::whereIn('id', $ids)->delete();
        });
        return response()->json(['ok' => true]);
    }

    public function move(Request $request)
    {
        $id = (int) $request->input('id');
        $newParent = $request->input('parent');
        $position = (int) $request->input('position', 0);
        $item = MenuItem::findOrFail($id);

        DB::transaction(function () use ($item, $newParent, $position) {
            $oldParent = $item->parent_id ?: null;
            $item->parent_id = $newParent ? (int) $newParent : null;

            $siblings = MenuItem::where('parent_id', $item->parent_id)->orderBy('sort_order')->get();
            $i = 0;
            foreach ($siblings as $sib) {
                $sib->sort_order = $i++;
                $sib->save();
            }
            foreach ($siblings as $sib) {
                if ($sib->sort_order >= $position) {
                    $sib->sort_order++;
                    $sib->save();
                }
            }
            $item->sort_order = $position;
            $item->save();

            if ($oldParent !== $item->parent_id) {
                $oldSibs = MenuItem::where('parent_id', $oldParent)->orderBy('sort_order')->get();
                $j = 0;
                foreach ($oldSibs as $s) {
                    $s->sort_order = $j++;
                    $s->save();
                }
            }
        });

        return response()->json(['ok' => true]);
    }

    protected function collectDescendantIds($id)
    {
        $ids = [$id];
        $queue = [$id];
        while ($queue) {
            $pid = array_shift($queue);
            $children = MenuItem::where('parent_id', $pid)->pluck('id');
            foreach ($children as $cid) {
                $ids[] = $cid;
                $queue[] = $cid;
            }
        }
        return $ids;
    }

    protected static function decodeLevels($levels)
    {
        if (!$levels) return null;
        $decoded = null;
        try { $decoded = json_decode($levels, true); } catch (\Throwable $e) {}
        return is_array($decoded) ? $decoded : null;
    }

    protected function extractIconsFromBlade(): array
    {
        $path = resource_path('views/pages/ui-icon-fontawesome.blade.php');
        if (!is_file($path)) return [];
        $content = @file_get_contents($path);
        if (!$content) return [];
        $icons = [];
        if (preg_match_all('/<i\s+class="([^"]*fa[a-z]?\s+fa-[a-z0-9\-]+[^"]*)"/i', $content, $m)) {
            foreach ($m[1] as $cls) {
                $tokens = preg_split('/\s+/', trim($cls));
                $style = null; $name = null;
                foreach ($tokens as $t) {
                    if (in_array($t, ['fa','fas','far','fab'], true)) { $style = $t; }
                    if (strpos($t, 'fa-') === 0) {
                        if (preg_match('/^(fa-fw|fa-lg|fa-\d+x|fa-rotate|fa-flip|fa-spin|fa-pulse|fa-stack.*|fa-inverse)$/', $t)) continue;
                        $name = $t; break;
                    }
                }
                if ($name) {
                    $style = $style ?: 'fa';
                    $icons[] = $style . ' ' . $name;
                }
            }
        }
        $icons = array_values(array_unique($icons));
        sort($icons);
        return $icons;
    }

    protected function extractIoniconsFromBlade(): array
    {
        $path = resource_path('views/pages/ui-icon-ionicons.blade.php');
        if (!is_file($path)) return [];
        $content = @file_get_contents($path);
        if (!$content) return [];
        $names = [];
        if (preg_match_all('/<ion-icon\s+name="([a-z0-9\-]+)"/i', $content, $m)) {
            foreach ($m[1] as $n) {
                if ($n) $names[] = $n;
            }
        }
        $names = array_values(array_unique($names));
        sort($names);
        return $names;
    }

    protected function extractGradientsFromHelper(): array
    {
        $path = resource_path('views/pages/helper-css.blade.php');
        if (!is_file($path)) return [];
        $content = @file_get_contents($path);
        if (!$content) return [];
        $classes = [];
        if (preg_match_all('/bg-gradient-[a-z\-]+/i', $content, $m)) {
            foreach ($m[0] as $c) {
                $classes[] = $c;
            }
        }
        $classes = array_values(array_unique($classes));
        $classes = array_values(array_filter($classes, function ($c) {
            $lc = strtolower($c);
            if (preg_match('/bg-gradient-(to|from|\d+)/i', $lc)) return false;
            if (in_array($lc, ['bg-gradient-white'], true)) return false;
            return true;
        }));
        sort($classes);
        return $classes;
    }
}