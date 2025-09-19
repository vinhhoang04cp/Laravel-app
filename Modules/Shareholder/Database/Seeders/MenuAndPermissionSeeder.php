<?php

namespace Modules\Shareholder\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuAndPermissionSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $adminRole = DB::table('roles')->where('name', 'admin')->first();

        $modules = [
            'Congress' => [
                'title' => 'Congress',
                'route' => 'congress.root',
                'icon'  => 'voyager-calendar',
                'order' => 4,
                'children' => [
                    [
                        'title' => 'Danh sách kỳ đại hội',
                        'route' => 'congresses.index',
                        'icon'  => 'voyager-list',
                        'order' => 1,
                        'permission' => 'browse_congresses',
                    ],
                    [
                        'title' => 'Tạo mới',
                        'route' => 'congresses.create',
                        'icon'  => 'voyager-plus',
                        'order' => 2,
                        'permission' => 'add_congresses',
                    ],
                ],
                'permissions' => [
                    'browse_congress_root',
                    'browse_congresses',
                    'add_congresses',
                    'edit_congresses',
                    'delete_congresses',
                    'view_congresses',
                    'import_congresses',
                    'browse_congress_shareholders',
                    'delete_congress_shareholders',
                ]
            ],

            'Shareholder' => [
                'title' => 'Shareholder',
                'route' => 'shareholders.root',
                'icon'  => 'voyager-group',
                'order' => 5,
                'children' => [
                    [
                        'title' => 'Danh sách cổ đông',
                        'route' => 'shareholders.index',
                        'icon'  => 'voyager-list',
                        'order' => 1,
                        'permission' => 'browse_shareholders',
                    ],
                    [
                        'title' => 'Tạo mới',
                        'route' => 'shareholders.create',
                        'icon'  => 'voyager-plus',
                        'order' => 2,
                        'permission' => 'add_shareholders',
                    ],
                ],
                'permissions' => [
                    'browse_shareholder_root',
                    'browse_shareholders',
                    'add_shareholders',
                    'edit_shareholders',
                    'delete_shareholders',
                    'view_shareholders',
                    'invite_shareholders',
                    'register_shareholders',
                ]
            ]
        ];

        foreach ($modules as $module) {
            // ===== Menu cha =====
            $parentId = DB::table('menu_items')->where('title', $module['title'])->value('id');
            if (!$parentId) {
                $parentId = DB::table('menu_items')->insertGetId([
                    'menu_id'    => 1,
                    'title'      => $module['title'],
                    'url'        => '',
                    'route'      => $module['route'],
                    'target'     => '_self',
                    'icon_class' => $module['icon'],
                    'color'      => null,
                    'parent_id'  => null,
                    'order'      => $module['order'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // ===== Menu con =====
            foreach ($module['children'] as $child) {
                $exists = DB::table('menu_items')->where('title', $child['title'])->first();
                if (!$exists) {
                    DB::table('menu_items')->insert([
                        'menu_id'    => 1,
                        'title'      => $child['title'],
                        'url'        => '',
                        'route'      => $child['route'],
                        'target'     => '_self',
                        'icon_class' => $child['icon'],
                        'color'      => null,
                        'parent_id'  => $parentId,
                        'order'      => $child['order'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            // ===== Quyền =====
            foreach ($module['permissions'] as $permKey) {
                $exists = DB::table('permissions')->where('key', $permKey)->first();
                if (!$exists) {
                    $id = DB::table('permissions')->insertGetId([
                        'key'        => $permKey,
                        'table_name' => strtolower($module['title']),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    if ($adminRole) {
                        DB::table('permission_role')->insert([
                            'permission_id' => $id,
                            'role_id'       => $adminRole->id,
                        ]);
                    }
                }
            }
        }
    }
}
