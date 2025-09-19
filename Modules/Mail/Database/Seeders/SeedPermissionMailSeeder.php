<?php

namespace Modules\Mail\Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class SeedPermissionMailSeeder extends Seeder
{
    public function run(): void
    {
        // Quyền menu cha
        Permission::firstOrCreate(['key' => 'browse_mail_root', 'table_name' => null]);

        // Các quyền khác
        $tables = ['mail_templates', 'mail_configs', 'mail_logs'];
        foreach ($tables as $tbl) {
            Permission::firstOrCreate(['key' => "browse_{$tbl}", 'table_name' => $tbl]);
            Permission::firstOrCreate(['key' => "read_{$tbl}",   'table_name' => $tbl]);
            if ($tbl !== 'mail_logs') {
                Permission::firstOrCreate(['key' => "add_{$tbl}",    'table_name' => $tbl]);
                Permission::firstOrCreate(['key' => "edit_{$tbl}",   'table_name' => $tbl]);
            }
            Permission::firstOrCreate(['key' => "delete_{$tbl}", 'table_name' => $tbl]);
        }

        // Quyền gửi thử email
        Permission::firstOrCreate(['key' => 'send_test_mail', 'table_name' => null]);

        // Gán tất cả quyền này cho admin
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            foreach (Permission::all() as $perm) {
                if (!$admin->permissions->contains($perm->id)) {
                    $admin->permissions()->attach($perm);
                }
            }
        }

        // Menu admin
        $menu = Menu::firstOrCreate(['name' => 'admin']);

        // Menu cha Mail
        $parent = MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'title' => 'Mail'],
            [
                'url' => '/admin/mail',
                'route' => 'mail.root',
                'icon_class' => 'voyager-mail',
                'order' => 60,
                'parent_id' => null,
            ]
        );

        // Menu con
        MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'title' => 'Templates', 'route' => 'mail.ui.templates.index'],
            [
                'parent_id' => $parent->id,
                'icon_class' => 'voyager-news',
                'order' => 1,
                'url' => '',
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'title' => 'Configs', 'route' => 'mail.ui.configs.index'],
            [
                'parent_id' => $parent->id,
                'icon_class' => 'voyager-settings',
                'order' => 2,
                'url' => '',
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'title' => 'Logs', 'route' => 'mail.ui.logs.index'],
            [
                'parent_id' => $parent->id,
                'icon_class' => 'voyager-list',
                'order' => 3,
                'url' => '',
            ]
        );
    }
}
