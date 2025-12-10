<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostPlatformTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'post_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'platform_id' => ['type' => 'INT', 'unsigned' => true]
        ]);

        $this->forge->addKey(['post_id', 'platform_id'], true);

        $this->forge->addForeignKey('post_id', 'posts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('platform_id', 'platforms', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('post_platform');
    }

    public function down()
    {
        $this->forge->dropTable('post_platform');
    }
}