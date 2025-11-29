<?php

return [
    'global' => [
        [
            'icon' => 'fa fa-sitemap',
            'title' => 'Dashboard',
            'url' => '/starter',
            'route-name' => 'starter',
            'levels' => [1,2,3]
        ],
        [
            'icon' => 'fa fa-cogs',
            'title' => 'APLIKASI',
            'url' => 'javascript:;',
            'caret' => true,
            'levels' => [1,2],
            'sub_menu' => [
                [
                    'icon' => 'fa fa-users',
                    'title' => 'Pengguna',
                    'url' => '/pengguna',
                    'route-name' => 'pengguna-index',
                    'levels' => [1]
                ],
                [
                    'icon' => 'fa fa-clipboard-list',
                    'title' => 'Audit Logs',
                    'url' => '/audit-logs',
                    'route-name' => 'audit-logs',
                    'levels' => [1]
                ],
                [
                    'icon' => 'fa fa-cogs',
                    'title' => 'App Settings',
                    'url' => '/app-settings',
                    'route-name' => 'app-settings',
                    'levels' => [1]
                ],
                [
                    'icon' => 'fa fa-key',
                    'title' => 'Menu Permissions',
                    'url' => '/menu-permissions',
                    'route-name' => 'menu-permissions',
                    'levels' => [1]
                ],
                [
                    'icon' => 'fa fa-list',
                    'title' => 'Menu Items',
                    'url' => '/menu-items',
                    'route-name' => 'menu-items-index',
                    'levels' => [1]
                ],
                [
                    'icon' => 'fa fa-sign-in-alt',
                    'title' => 'Login Logs',
                    'url' => '/login-logs',
                    'route-name' => 'login-logs',
                    'levels' => [1,2]
                ],
            ]
        ],
    ],
    
];