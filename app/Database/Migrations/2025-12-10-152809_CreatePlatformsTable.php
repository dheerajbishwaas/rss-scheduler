<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePlatformsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 64],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('platforms');
    }

    public function down()
    {
        $this->forge->dropTable('platforms');
    }
}
