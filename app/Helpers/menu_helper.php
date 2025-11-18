<?php

if (!function_exists('menuUser')) {
    function menuUser()
    {
        return [
            [
                'heading' => 'Manajemen Program',
                'items' => [
                    [
                        'name' => 'Kategori',
                        'url' => 'category.index',
                        'icon' => '<i class="ki-duotone ki-folder fs-2"><span class="path1"></span><span class="path2"></span></i>',
                        'permissions' => [
                            'Manajemen Kategori' => [
                                ['name' => 'category.index', 'description' => 'Lihat daftar kategori'],
                                ['name' => 'category.create', 'description' => 'Tambah kategori'],
                                ['name' => 'category.edit', 'description' => 'Ubah kategori'],
                                ['name' => 'category.destroy', 'description' => 'Hapus kategori'],
                            ]
                        ]
                    ],
                ]
            ],
            [
                'heading' => 'Manajemen Pengguna',
                'items' => [
                    [
                        'name' => 'Pengguna Sistem',
                        'url' => 'user.index',
                        'icon' => '<i class="ki-duotone ki-profile-user fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>',
                        'permissions' => [
                            'Manajemen Pengguna Sistem' => [
                                ['name' => 'user.index', 'description' => 'Lihat daftar pengguna sistem'],
                                ['name' => 'user.create', 'description' => 'Tambah pengguna sistem'],
                                ['name' => 'user.edit', 'description' => 'Ubah pengguna sistem'],
                                ['name' => 'user.destroy', 'description' => 'Hapus pengguna sistem'],
                                ['name' => 'user.resetPassword', 'description' => 'Reset password pengguna sistem'],
                            ]
                        ]
                    ],
                    [
                        'name' => 'Peran & Hak Akses',
                        'url' => 'role-permission.index',
                        'icon' => '<i class="ki-duotone ki-security-user fs-2"><span class="path1"></span><span class="path2"></span></i>',
                        'permissions' => [
                            'Manajemen Hak Akses' => [
                                ['name' => 'role-permission.index', 'description' => 'Lihat daftar peran'],
                                ['name' => 'role-permission.create', 'description' => 'Tambah peran dan hak akses'],
                                ['name' => 'role-permission.edit', 'description' => 'Ubah peran dan hak akses'],
                                ['name' => 'role-permission.destroy', 'description' => 'Hapus peran dan hak akses'],
                            ]
                        ]
                    ],
                ]
            ],
            [
                'heading' => 'Lainnya',
                'items' => [
                    [
                        'name' => 'Pengaturan Aplikasi',
                        'url' => 'application-setting.general',
                        'icon' => '<i class="ki-duotone ki-setting-2 fs-2"><span class="path1"></span><span class="path2"></span></i>',
                        'permissions' => [
                            'Pengaturan Aplikasi' => [
                                ['name' => 'application-setting.general', 'description' => 'Pengaturan umum'],
                                ['name' => 'application-setting.social-media', 'description' => 'Pengaturan media sosial'],
                            ]
                        ]
                    ],
                    [
                        'name' => 'Hak Akses Aplikasi',
                        'url' => 'permission-application.create',
                        'icon' => '<i class="ki-duotone ki-shield fs-2"><span class="path1"></span><span class="path2"></span></i>',
                        'role_only' => ['Super Admin']
                    ],
                    [
                        'name' => 'Log Aktivitas',
                        'url' => 'activity-log.index',
                        'icon' => '<i class="ki-duotone ki-pointers fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>',
                        'permissions' => [
                            'Manajemen Log Aktivitas' => [
                                ['name' => 'activity-log.index', 'description' => 'Lihat daftar log aktivitas'],
                                ['name' => 'activity-log.show', 'description' => 'Lihat detail log aktivitas'],
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}


if (!function_exists('getViewPermission')) {
    function getViewPermission(array $item): ?string
    {
        if (empty($item['permissions']) || !is_array($item['permissions'])) {
            return null;
        }

        $firstGroup = array_key_first($item['permissions']);
        return $item['permissions'][$firstGroup][0]['name'] ?? null;
    }
}

if (!function_exists('isMenuActive')) {
    function isMenuActive(array $item, string $current): bool
    {
        if (!empty($item['child'])) {
            foreach ($item['child'] as $child) {
                if (isMenuActive($child, $current)) {
                    return true;
                }
            }
        }

        if (!empty($item['url'])) {
            if ($current === $item['url'] || str_starts_with($current, str_replace('.index', '', $item['url']))) {
                return true;
            }
        }

        if (!empty($item['permissions'])) {
            foreach ($item['permissions'] as $group) {
                foreach ($group as $perm) {
                    if ($current === $perm['name'] || str_starts_with($current, explode('.', $perm['name'])[0])) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}

if (!function_exists('getAllPermissionsFromMenu')) {
    function getAllPermissionsFromMenu()
    {
        $menus = menuUser();
        $permissions = [];

        $extractPermissions = function ($items, $parentIcon = null) use (&$extractPermissions, &$permissions) {
            foreach ($items as $item) {
                $itemIcon = $item['icon'] ?? $parentIcon;

                if (!empty($item['permissions'])) {
                    foreach ($item['permissions'] as $groupName => $perms) {
                        foreach ($perms as $perm) {
                            $permissions[] = [
                                'group' => $groupName,
                                'icon' => $itemIcon,
                                'name' => $perm['name'],
                                'description' => $perm['description'],
                            ];
                        }
                    }
                }

                if (!empty($item['child'])) {
                    $extractPermissions($item['child'], $itemIcon);
                }
            }
        };

        foreach ($menus as $menuGroup) {
            if (!empty($menuGroup['items'])) {
                $extractPermissions($menuGroup['items']);
            }
        }

        return $permissions;
    }
}
