<?php

namespace Modules\Mail\Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class SeedMenuMailSeeder extends Seeder
{
    public function run(): void
    {
        // ========================= 1) Tạo permissions =========================
        $perms = [
            // Root menu
            ['key' => 'browse_mail_root', 'table_name' => null],

            // Templates
            ['key' => 'browse_mail_templates', 'table_name' => 'mail_templates'],
            ['key' => 'read_mail_templates',   'table_name' => 'mail_templates'],
            ['key' => 'add_mail_templates',    'table_name' => 'mail_templates'],
            ['key' => 'edit_mail_templates',   'table_name' => 'mail_templates'],
            ['key' => 'delete_mail_templates', 'table_name' => 'mail_templates'],

            // Configs
            ['key' => 'browse_mail_configs',   'table_name' => 'mail_configs'],
            ['key' => 'read_mail_configs',     'table_name' => 'mail_configs'],
            ['key' => 'add_mail_configs',      'table_name' => 'mail_configs'],
            ['key' => 'edit_mail_configs',     'table_name' => 'mail_configs'],
            ['key' => 'delete_mail_configs',   'table_name' => 'mail_configs'],

            // Logs
            ['key' => 'browse_mail_logs',      'table_name' => 'mail_logs'],
            ['key' => 'read_mail_logs',        'table_name' => 'mail_logs'],
            ['key' => 'delete_mail_logs',      'table_name' => 'mail_logs'],
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate($p);
        }

        // ========================= 2) Gán quyền cho admin =====================
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            foreach ($perms as $p) {
                $perm = Permission::where('key', $p['key'])->first();
                if ($perm && !$admin->permissions->contains($perm->id)) {
                    $admin->permissions()->attach($perm);
                }
            }
        }

        // ========================= 3) Thêm menu Mail cha ======================
        $menu = Menu::firstOrCreate(['name' => 'admin']);

        $parent = MenuItem::firstOrCreate(
            [
                'menu_id' => $menu->id,
                'title'   => 'Mail',
                'route'   => 'mail.root', // Route giả để Voyager check quyền
            ],
            [
                'url'        => '',
                'icon_class' => 'voyager-mail',
                'order'      => 60,
            ]
        );

        // ========================= 4) Menu con ================================
        // Templates
        MenuItem::firstOrCreate(
            [
                'menu_id' => $menu->id,
                'title'   => 'Templates',
                'route'   => 'mail.ui.templates.index',
            ],
            [
                'parent_id'  => $parent->id,
                'icon_class' => 'voyager-news',
                'order'      => 1,
                'url'        => '',
            ]
        );

        // Configs
        MenuItem::firstOrCreate(
            [
                'menu_id' => $menu->id,
                'title'   => 'Configs',
                'route'   => 'mail.ui.configs.index',
            ],
            [
                'parent_id'  => $parent->id,
                'icon_class' => 'voyager-settings',
                'order'      => 2,
                'url'        => '',
            ]
        );

        // Logs
        MenuItem::firstOrCreate(
            [
                'menu_id' => $menu->id,
                'title'   => 'Logs',
                'route'   => 'mail.ui.logs.index',
            ],
            [
                'parent_id'  => $parent->id,
                'icon_class' => 'voyager-list',
                'order'      => 3,
                'url'        => '',
            ]
        );
    }
}
