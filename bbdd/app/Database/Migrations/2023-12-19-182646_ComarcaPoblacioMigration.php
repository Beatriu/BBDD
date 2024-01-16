<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ComarcaPoblacioMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_comarca'          => [
                'type'           => 'INT',
                'constraint'     => 2,
                'unsigned'       => true,
                'null'          => false,
            ],
            'nom_comarca'       => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'null'          => false,
            ],
        ]);
        $this->forge->addKey('id_comarca', true);
        $this->forge->createTable('comarca');

        $this->forge->addField([
            'id_poblacio'          => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'null'          => false,
            ],
            'codi_postal'          => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null'          => false,
            ],
            'nom_poblacio'       => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'null'          => false,
            ],
            'id_comarca'       => [
                'type'       => 'INT',
                'constraint' => 2,
                'unsigned'       => true,
                'null'          => false,
            ],
            
        ]);
        $this->forge->addForeignKey('id_comarca', 'comarca', 'id_comarca');
        $this->forge->addKey('id_poblacio', true);
        $this->forge->createTable('poblacio');
    }

    public function down()
    {
        $this->forge->dropTable('comarca');
        $this->forge->dropTable('poblacio');
    }
}
