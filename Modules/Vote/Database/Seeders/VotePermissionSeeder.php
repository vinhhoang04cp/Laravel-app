<?php

namespace Modules\Vote\Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

class VotePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ====== PERMISSIONS ======
        $permissions = [
            ['key' => 'browse_vote_root', 'table_name' => null],
            ['key' => 'browse_vote_sessions', 'table_name' => 'vote_sessions'],
            ['key' => 'read_vote_sessions',   'table_name' => 'vote_sessions'],
            ['key' => 'add_vote_sessions',    'table_name' => 'vote_sessions'],
            ['key' => 'edit_vote_sessions',   'table_name' => 'vote_sessions'],
            ['key' => 'delete_vote_sessions', 'table_name' => 'vote_sessions'],
            ['key' => 'browse_votes',         'table_name' => 'votes'],
            ['key' => 'read_votes',           'table_name' => 'votes'],
            ['key' => 'delete_votes',         'table_name' => 'votes'],
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

        // Tạo menu cha "Vote"
        $voteMenu = MenuItem::firstOrCreate([
            'menu_id' => $menu->id,
            'title'   => 'Vote',
            'url'     => '',
            'route'   => 'vote.root',
            'icon_class' => 'voyager-check',
            'order'   => 99,
        ]);

        // Menu con: Vote Sessions
        MenuItem::firstOrCreate([
            'menu_id'   => $menu->id,
            'parent_id' => $voteMenu->id,
            'title'     => 'Vote Sessions',
            'url'       => '',
            'route'     => 'vote.ui.sessions.index',
            'icon_class'=> 'voyager-list',
            'order'     => 1,
        ]);

        // Menu con: Votes
        MenuItem::firstOrCreate([
            'menu_id'   => $menu->id,
            'parent_id' => $voteMenu->id,
            'title'     => 'Votes',
            'url'       => '',
            'route'     => 'vote.ui.votes.index',
            'icon_class'=> 'voyager-check-circle',
            'order'     => 2,
        ]);

        $this->command->info('Đã tạo Permissions & Menu cho Vote thành công!');
    }
}
