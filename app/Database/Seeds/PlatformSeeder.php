<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PlatformSeeder extends Seeder
{
    public function run()
    {
        $platforms = [
            ['name' => 'facebook'],
            ['name' => 'x'],
            ['name' => 'linkedin'],
            ['name' => 'instagram'],
            ['name' => 'tiktok'],
            ['name' => 'threads'],
            ['name' => 'bluesky']
        ];

        $this->db->table('platforms')->insertBatch($platforms);
    }
}
