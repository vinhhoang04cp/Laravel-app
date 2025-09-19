<?php

namespace Modules\Report\Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

class ReportPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ====== PERMISSIONS ======
        $permissions = [
            ['key' => 'browse_report_root', 'table_name' => null],
            ['key' => 'browse_attendance_reports', 'table_name' => 'reports'],
            ['key' => 'export_attendance_reports', 'table_name' => 'reports'],
            ['key' => 'browse_voting_reports', 'table_name' => 'reports'],
            ['key' => 'export_voting_reports', 'table_name' => 'reports'],
            ['key' => 'browse_capital_contribution_reports', 'table_name' => 'reports'],
            ['key' => 'export_capital_contribution_reports', 'table_name' => 'reports'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate($perm);
        }

        // ====== MENU ======
        $menu = Menu::where('name', 'admin')->first();
        if (!$menu) {
            $this->command->error('Menu "admin" không tồn tại, hãy kiểm tra Voyager menu');
            return;
        }

        // Tạo menu cha Bao cao
        $reportMenu = MenuItem::firstOrCreate([
            'menu_id' => $menu->id,
            'title' => 'Report',
            'url' => '',
            'route' => 'report.root',
            'icon_class' => 'voyager-documentation',
            'order' => 99,
        ]);

        // Menu con: Bao cao gop von
        MenuItem::firstOrCreate([
            'menu_id'   => $menu->id,
            'parent_id' => $reportMenu->id,
            'title'     => 'Bao cao gop von',
            'url'       => '',
            'route'     => 'reports.capital_contributions.index',
            'icon_class'=> 'voyager-documentation',
            'order'     => 1,
        ]);

        // Menu con: Bao cao bieu quyet
        MenuItem::firstOrCreate([
            'menu_id'   => $menu->id,
            'parent_id' => $reportMenu->id,
            'title'     => 'Bao cao bieu quyet',
            'url'       => '',
            'route'     => 'reports.votings.index',
            'icon_class'=> 'voyager-documentation',
            'order'     => 2,
        ]);

        // Menu con: Bao cao tham du
        MenuItem::firstOrCreate([
            'menu_id'   => $menu->id,
            'parent_id' => $reportMenu->id,
            'title'     => 'Bao cao tham du',
            'url'       => '',
            'route'     => 'reports.attendances.index',
            'icon_class'=> 'voyager-documentation',
            'order'     => 3,
        ]);

        $this->command->info('Đã tạo Permissions & Menu cho Report thành công!');
    }
}
