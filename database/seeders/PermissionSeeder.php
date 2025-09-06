<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = getAllPermissionsFromMenu();

        $expectedGroups = collect($permissions)->pluck('group')->unique()->toArray();
        $expectedPermissions = collect($permissions)->pluck('name')->toArray();

        // === Sinkronisasi Permission Groups ===
        foreach ($permissions as $perm) {
            $group = PermissionGroup::updateOrCreate(
                ['name' => $perm['group']],
                ['icon' => $perm['icon'] ?? null]
            );

            Permission::updateOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                [
                    'description' => $perm['description'],
                    'group_id'    => $group->id,
                ]
            );
        }

        // === Hapus Permission yang tidak ada lagi ===
        Permission::whereNotIn('name', $expectedPermissions)->delete();

        // === Hapus Group yang tidak punya Permission lagi ===
        PermissionGroup::whereNotIn('name', $expectedGroups)
            ->orWhereDoesntHave('permissions')
            ->delete();
    }
}
