<?php

namespace Modules\Mail\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Mail\App\Models\MailTemplate;

class SeedTemplateMailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MailTemplate::firstOrCreate(
            ['code' => 'welcome_email'],
            [
                'name' => 'Welcome Email',
                'subject' => 'Xin chào {{ $name }}',
                'body' => '<p>Chào {{ $name }}, số {{ $phone }} - địa chỉ {{ $address }}</p>',
                'placeholders' => ['name','phone','address'],
                'is_html' => true,
                'enabled' => true
            ]
        );
    }
}
