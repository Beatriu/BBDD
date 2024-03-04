<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BackTicketMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_back' => [
                'type' => 'INT',
                'constraint' => '12',
                'null' => false,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tipus_alerta' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => false,
            ],
            'data_backticket' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'informacio' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
                'null' => false,
            ]
        ]);
        $this->forge->addKey('id_back', true);
        $this->forge->createTable('backticket');
    }
    public function down()
    {
        $this->forge->dropTable('backticket');
    }
}
