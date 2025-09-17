<?php

if (!function_exists('menuUser')) {
    function menuUser()
    {
        return [
            [
                'heading' => 'Artikel',
                'items' => [
                    [
                        'name' => 'Kategori',
                        'url' => 'article-category.index',
                        'icon' => '<i class="ki-duotone ki-folder fs-2"><span class="path1"></span><span class="path2"></span></i>',
                        'permissions' => [
                            'Manajemen Kategori Artikel' => [
                                ['name' => 'article-category.index', 'description' => 'Lihat daftar kategori'],
                                ['name' => 'article-category.create', 'description' => 'Tambah kategori'],
                                ['name' => 'article-category.edit', 'description' => 'Ubah kategori'],
                                ['name' => 'article-category.destroy', 'description' => 'Hapus kategori'],
                            ]
                        ]
                    ],
                    [
                        'name' => 'Tag',
                        'url' => 'article-tag.index',
                        'icon' => '<i class="ki-duotone ki-folder fs-2"><span class="path1"></span><span class="path2"></span></i>',
                        'permissions' => [
                            'Manajemen Tag Artikel' => [
                                ['name' => 'article-tag.index', 'description' => 'Lihat daftar tag'],
                                ['name' => 'article-tag.create', 'description' => 'Tambah tag'],
                                ['name' => 'article-tag.edit', 'description' => 'Ubah tag'],
                                ['name' => 'article-tag.destroy', 'description' => 'Hapus tag'],
                            ]
                        ]
                    ]
                ]
            ],
            [
                'heading' => 'Manajemen Pengguna',
                'items' => [
                    [
                        'name' => 'Pengguna',
                        'url' => 'user.index',
                        'icon' => '<i class="ki-duotone ki-profile-user fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>',
                        'permissions' => [
                            'Manajemen Pengguna' => [
                                ['name' => 'user.index', 'description' => 'Lihat daftar pengguna'],
                                ['name' => 'user.create', 'description' => 'Tambah pengguna'],
                                ['name' => 'user.edit', 'description' => 'Ubah pengguna'],
                                ['name' => 'user.destroy', 'description' => 'Hapus pengguna'],
                                ['name' => 'user.resetPassword', 'description' => 'Reset password pengguna'],
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
                        'name' => 'Hak Akses Aplikasi',
                        'url' => 'permission-application.create',
                        'icon' => '<i class="ki-duotone ki-shield fs-2"><span class="path1"></span><span class="path2"></span></i>',
                        'role_only' => ['Super Admin']
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

        foreach ($menus as $menuGroup) {
            foreach ($menuGroup['items'] as $item) {
                if (!empty($item['permissions'])) {
                    foreach ($item['permissions'] as $groupName => $perms) {
                        foreach ($perms as $perm) {
                            $permissions[] = [
                                'group' => $groupName,
                                'icon' => $item['icon'],
                                'name' => $perm['name'],
                                'description' => $perm['description'],
                            ];
                        }
                    }
                }
            }
        }

        return $permissions;
    }
}
