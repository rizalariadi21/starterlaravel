@php
	$appSidebarClass = (!empty($appSidebarTransparent)) ? 'app-sidebar-transparent' : '';
	$appSidebarAttr  = (!empty($appSidebarLight)) ? '' : ' data-bs-theme=dark';
@endphp
<!-- BEGIN #sidebar -->
<div id="sidebar" class="app-sidebar {{ $appSidebarClass }}" {{ $appSidebarAttr }}>
	<!-- BEGIN scrollbar -->
	<div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
		<div class="menu">
			@if (!$appSidebarSearch)
			<div class="menu-profile">
				<div class="menu-profile-link">
					<div class="menu-profile-cover with-shadow"></div>
					<div class="menu-profile-image">
						<i class="fa fa-user-circle fa-2x"></i>
					</div>
					<div class="menu-profile-info">
						<div class="d-flex align-items-center">
							<div class="flex-grow-1">
								{{ Auth::check() ? (Auth::user()->pengguna ?? 'Pengguna') : 'Pengguna' }}
							</div>
						</div>
						<small>
							@php $lvl = Auth::check() ? (Auth::user()->level ?? null) : null; $role = ($lvl===1?'Admin':($lvl===2?'User':($lvl===3?'Viewer':('Level '.(string)$lvl)))); @endphp
							{{ $role }}
						</small>
					</div>
				</div>
			</div>
			@endif
			
			@if ($appSidebarSearch)
			<div class="menu-search mb-n3">
        <input type="text" class="form-control" placeholder="Sidebar menu filter..." data-sidebar-search="true" />
			</div>
			@endif
			
			<div class="menu-header">Navigation</div>
			
			@php
				$currentUrl = (Request::path() != '/') ? '/'. Request::path() : '/';
				
				if (!function_exists('renderSubMenu')) {
				function renderSubMenu($value, $currentUrl) {
					$subMenu = '';
					$GLOBALS['sub_level'] += 1 ;
					$GLOBALS['active'][$GLOBALS['sub_level']] = '';
					$currentLevel = $GLOBALS['sub_level'];
					foreach ($value as $key => $menu) {
						$GLOBALS['subparent_level'] = '';
						
						$subSubMenu = '';
						$hasSub = (!empty($menu['sub_menu'])) ? 'has-sub' : '';
						$hasCaret = (!empty($menu['sub_menu'])) ? '<div class="menu-caret"></div>' : '';
						$hasHighlight = (!empty($menu['highlight'])) ? '<i class="fa fa-paper-plane text-theme ms-1"></i>' : '';
						$hasTitle = (!empty($menu['title'])) ? '<div class="menu-text">'. $menu['title'] . $hasHighlight .'</div>' : '';
						
						if (!empty($menu['sub_menu'])) {
							$subSubMenu .= '<div class="menu-submenu">';
							$subSubMenu .= renderSubMenu($menu['sub_menu'], $currentUrl);
							$subSubMenu .= '</div>';
						}
						
						$active = (!empty($menu['route-name']) && (Route::currentRouteName() == $menu['route-name'])) ? 'active' : '';
						
						if ($active) {
							$GLOBALS['parent_active'] = true;
							$GLOBALS['active'][$GLOBALS['sub_level'] - 1] = true;
						}
						if (!empty($GLOBALS['active'][$currentLevel])) {
							$active = 'active';
						}
						$subMenu .= '
							<div class="menu-item '. $hasSub .' '. $active .'">
								<a href="'. $menu['url'] .'" class="menu-link">' . $hasTitle . $hasCaret .'</a>
								'. $subSubMenu .'
							</div>
						';
					}
					return $subMenu;
				}
				}
				
$menuSource = isset($sidebarMenu) ? $sidebarMenu : \App\Models\MenuItem::buildMenuArrayWithFallback();
$currentUserLevel = (Auth::check()) ? (Auth::user()->level ?? null) : null;
$overrides = \App\Models\MenuPermission::all()->keyBy('route_name');
if (!function_exists('filterMenuByRole')) {
function filterMenuByRole($items, $level, $overrides) {
    $result = [];
    $roleName = null;
    if ($level === 1 || $level === '1') $roleName = 'admin';
    elseif ($level === 2 || $level === '2') $roleName = 'user';
    elseif ($level === 3 || $level === '3') $roleName = 'viewer';
    $roleAliases = [];
    if ($roleName) $roleAliases[] = $roleName;
    if ($level !== null) {
        $roleAliases[] = (string) $level;
        $roleAliases[] = 'level'. (string) $level;
    }
    foreach ($items as $item) {
        $allowedLevels = null;
        $routeName = !empty($item['route-name']) ? $item['route-name'] : null;
        if ($routeName && isset($overrides[$routeName])) {
            $ov = $overrides[$routeName];
            if (is_array($ov->allowed_levels) && count($ov->allowed_levels) > 0) {
                $allowedLevels = array_map('intval', (array) $ov->allowed_levels);
            }
        }
        if ($allowedLevels === null) {
            if (!empty($item['levels'])) {
                $allowedLevels = array_map('intval', (array) $item['levels']);
            } elseif (!empty($item['roles'])) {
                $mapped = [];
                foreach ((array) $item['roles'] as $r) {
                    if ($r === 'admin') $mapped[] = 1;
                    elseif ($r === 'user') $mapped[] = 2;
                    elseif ($r === 'viewer') $mapped[] = 3;
                    elseif (is_numeric($r)) $mapped[] = (int) $r;
                    elseif (preg_match('/^level(\d+)$/', $r, $m)) $mapped[] = (int) $m[1];
                }
                $allowedLevels = $mapped;
            }
        }
        $allowed = $allowedLevels !== null && $level !== null ? in_array((int)$level, (array) $allowedLevels, true) : false;
        if (!$allowed) { continue; }
        if (!empty($item['sub_menu'])) {
            $item['sub_menu'] = filterMenuByRole($item['sub_menu'], $level, $overrides);
            if (empty($item['sub_menu']) && (!empty($item['caret']) || ($item['url'] ?? '') === 'javascript:;')) {
                continue;
            }
        }
        if (!empty($item['route-name']) && !Route::has($item['route-name'])) {
            continue;
        }
        $result[] = $item;
    }
    return $result;
}
}
$menuList = filterMenuByRole($menuSource, $currentUserLevel, $overrides);
			foreach ($menuList as $key => $menu) {
				$GLOBALS['parent_active'] = '';
				
				$hasSub = (!empty($menu['sub_menu'])) ? 'has-sub' : '';
				$hasCaret = (!empty($menu['caret'])) ? '<div class="menu-caret"></div>' : '';
				
				// Map FontAwesome icons to Ionicons with gradient colors
				$iconMap = [
					'fa fa-sitemap' => ['icon' => 'analytics-outline', 'gradient' => 'bg-gradient-blue'],
					'fa fa-microchip' => ['icon' => 'hardware-chip-outline', 'gradient' => 'bg-gradient-purple'],
					'fa fa-hdd' => ['icon' => 'mail-outline', 'gradient' => 'bg-gradient-teal'],
					'fab fa-simplybuilt' => ['icon' => 'apps-outline', 'gradient' => 'bg-gradient-orange'],
					'fa fa-gem' => ['icon' => 'diamond-outline', 'gradient' => 'bg-gradient-pink'],
					'fa fa-list-ol' => ['icon' => 'list-outline', 'gradient' => 'bg-gradient-indigo'],
					'fa fa-table' => ['icon' => 'grid-outline', 'gradient' => 'bg-gradient-cyan'],
					'fa fa-cash-register' => ['icon' => 'cash-outline', 'gradient' => 'bg-gradient-green'],
					'fa fa-star' => ['icon' => 'star-outline', 'gradient' => 'bg-gradient-yellow'],
					'fa fa-envelope' => ['icon' => 'mail-outline', 'gradient' => 'bg-gradient-red'],
					'fa fa-chart-pie' => ['icon' => 'pie-chart-outline', 'gradient' => 'bg-gradient-blue'],
					'fa fa-crown' => ['icon' => 'trophy-outline', 'gradient' => 'bg-gradient-orange'],
					'fa fa-calendar' => ['icon' => 'calendar-outline', 'gradient' => 'bg-gradient-purple'],
					'fa fa-map' => ['icon' => 'map-outline', 'gradient' => 'bg-gradient-teal'],
					'fa fa-image' => ['icon' => 'images-outline', 'gradient' => 'bg-gradient-pink'],
					'fa fa-cogs' => ['icon' => 'settings-outline', 'gradient' => 'bg-gradient-gray'],
					'fa fa-gift' => ['icon' => 'gift-outline', 'gradient' => 'bg-gradient-red'],
					'fa fa-key' => ['icon' => 'key-outline', 'gradient' => 'bg-gradient-indigo'],
					'fa fa-cube' => ['icon' => 'cube-outline', 'gradient' => 'bg-gradient-cyan'],
					'fa fa-medkit' => ['icon' => 'medkit-outline', 'gradient' => 'bg-gradient-green'],
					'fa fa-align-left' => ['icon' => 'menu-outline', 'gradient' => 'bg-gradient-blue'],
				];
				
                $hasIcon = '';
                if (!empty($menu['icon'])) {
                    if (strpos($menu['icon'], 'ion:') === 0) {
                        $raw = substr($menu['icon'], 4);
                        $parts = preg_split('/\s+/', trim($raw));
                        $ionName = isset($parts[0]) ? $parts[0] : '';
                        $ionClass = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
                        $hasIcon = '<div class="menu-icon"><ion-icon name="'. $ionName .'"'. ($ionClass ? ' class="'. $ionClass .'"' : '') .'></ion-icon></div>';
                    } elseif (isset($iconMap[$menu['icon']])) {
                        $ionicon = $iconMap[$menu['icon']];
                        $hasIcon = '<div class="menu-icon"><ion-icon name="'. $ionicon['icon'] .'" class="'. $ionicon['gradient'] .'"></ion-icon></div>';
                    } else {
                        // Fallback to FontAwesome if not mapped
                        $hasIcon = '<div class="menu-icon"><i class="'. $menu['icon'] .'"></i></div>';
                    }
                }
				
				$hasImg = (!empty($menu['img'])) ? '<div class="menu-icon-img"><img src="'. $menu['img'] .'" /></div>' : '';
				$hasLabel = (!empty($menu['label'])) ? '<span class="menu-label">'. $menu['label'] .'</span>' : '';
				$hasTitle = (!empty($menu['title'])) ? '<div class="menu-text">'. $menu['title'] . $hasLabel .'</div>' : '';
				$hasBadge = (!empty($menu['badge'])) ? '<div class="menu-badge">'. $menu['badge'] .'</div>' : '';
					
					$subMenu = '';
					
					if (!empty($menu['sub_menu'])) {
						$GLOBALS['sub_level'] = 0;
						$subMenu .= '<div class="menu-submenu">';
						$subMenu .= renderSubMenu($menu['sub_menu'], $currentUrl);
						$subMenu .= '</div>';
					}
					$active = (!empty($menu['route-name']) && (Route::currentRouteName() == $menu['route-name'])) ? 'active' : '';
					$active = (empty($active) && !empty($GLOBALS['parent_active'])) ? 'active' : $active;
					echo '
						<div class="menu-item '. $hasSub .' '. $active .'">
							<a href="'. $menu['url'] .'" class="menu-link">
								'. $hasImg .'
								'. $hasIcon .'
								'. $hasTitle .'
								'. $hasBadge .'
								'. $hasCaret .'
							</a>
							'. $subMenu .'
						</div>
					';
				}
			@endphp
			<!-- BEGIN minify-button -->
			<div class="menu-item d-flex">
				<a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
			</div>
			<!-- END minify-button -->
		
		</div>
		<!-- END menu -->
	</div>
	<!-- END scrollbar -->
</div>
<div class="app-sidebar-bg" {{ $appSidebarAttr }}></div>
<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
<!-- END #sidebar -->
