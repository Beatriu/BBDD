<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SsttMigration extends Migration
{
    /**
     * Funció per arrencar la migració.
     *
     * @author Beatriu Badia Sala
     */
    public function up()
    {
        // Taula SSTT
        $this->forge->addField([
            'id_sstt' =>[
                'type' => 'VARCHAR',
                'constraint' => '4',
                'null' => false,
            ],
            'nom_sstt' =>[
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => false,
            ],
            'adreca_fisica_sstt' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => false,
            ],
            'telefon_sstt' => [
                'type' => 'VARCHAR',
                'constraint' => '12',
                'null' => false,
            ],
            'correu_sstt' =>[
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => false,
            ]
        ]);
        $this->forge->addKey('id_sstt', true);
        $this->forge->createTable('sstt');
    }

    /**
     * Funció per revertir la migració.
     *
     * @author Beatriu Badia Sala
     */
    public function down()
    {
        $this->forge->dropTable('sstt');
    }
}
