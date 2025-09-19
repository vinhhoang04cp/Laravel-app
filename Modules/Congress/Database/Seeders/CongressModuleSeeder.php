<?php

namespace Modules\Congress\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CongressModuleSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        /**
         * ====== PERMISSIONS ======
         */
        $permissions = [
            ['key' => 'browse_congress_root', 'table_name' => null],
            ['key' => 'browse_congresses', 'table_name' => 'congresses'],
            ['key' => 'add_congresses', 'table_name' => 'congresses'],
            ['key' => 'edit_congresses', 'table_name' => 'congresses'],
            ['key' => 'delete_congresses', 'table_name' => 'congresses'],
            ['key' => 'import_congresses', 'table_name' => 'congresses'],
            ['key' => 'browse_congress_shareholders', 'table_name' => 'congress_shareholders'],
            ['key' => 'delete_congress_shareholders', 'table_name' => 'congress_shareholders'],
            ['key' => 'browse_shareholders_queue', 'table_name' => 'congress_shareholders'],
        ];

        foreach ($permissions as $perm) {
            $exists = DB::table('permissions')->where('key', $perm['key'])->first();
            if (!$exists) {
                DB::table('permissions')->insert([
                    'key'        => $perm['key'],
                    'table_name' => $perm['table_name'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        /**
         * ====== MENU ======
         */
        $menu = DB::table('menus')->where('name', 'admin')->first();
        if (!$menu) {
            $menuId = DB::table('menus')->insertGetId([
                'name'       => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $menuId = $menu->id;
        }

        $order = DB::table('menu_items')->where('menu_id', $menuId)->max('order') + 1;

        // Tạo menu cha "Congress"
        $parentId = DB::table('menu_items')->insertGetId([
            'menu_id'    => $menuId,
            'title'      => 'Congress',
            'icon_class' => 'voyager-people',
            'url'        => '',
            'route'      => 'congress.root',
            'target'     => '_self',
            'color'      => null,
            'parent_id'  => null,
            'order'      => $order,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Tạo submenu "Danh sách kỳ đại hội"
        DB::table('menu_items')->insert([
            'menu_id'    => $menuId,
            'title'      => 'Danh sách kỳ đại hội',
            'icon_class' => 'voyager-list',
            'url'        => '',
            'route'      => 'congresses.index',
            'target'     => '_self',
            'color'      => null,
            'parent_id'  => $parentId,
            'order'      => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
