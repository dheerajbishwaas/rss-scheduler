<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImageUrlToPosts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('posts', [
            'image_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'after'      => 'priority'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('posts', 'image_url');
    }
}
