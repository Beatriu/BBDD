<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TipusIntervencioCursIntervencioMigration extends Migration
{
    /**
     * Funció per arrencar la migració.
     *
     * @author Blai Burgués Vicente
     */
    public function up()
    {
        // Taula tipus intervencio
        $this->forge->addField([
            'id_tipus_intervencio'          => [
                'type'           => 'INT',
                'constraint'     => 2,
                'unsigned'       => true,
                'null'          => false,
                'auto_increment' => TRUE
            ],
            'nom_tipus_intervencio'       => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'null'          => false,
            ],
        ]);
        $this->forge->addKey('id_tipus_intervencio', true);
        $this->forge->createTable('tipus_intervencio');

        // Taula curs
        $this->forge->addField([
            'id_curs'          => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null'          => false,
                'auto_increment' => TRUE
            ],
            'cicle'       => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
                'null'          => false,
            ],
            'titol'       => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'          => false,
            ],
            'curs'       => [
                'type'       => 'INT',
                'constraint' => 1,
                'unsigned'       => true,
                'null'          => false,
            ],
            
        ]);
        $this->forge->addKey('id_curs', true);
        $this->forge->createTable('curs');

        // Taula intervencio
        $this->forge->addField([
            'id_intervencio'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '36',
                'null'          => false,
            ],
            'descripcio_intervencio'       => [
                'type'       => 'VARCHAR',
                'constraint' => '512',
                'null'          => false,
            ],
            'id_tiquet'       => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'          => false,
            ],
            'data_intervencio'       => [
                'type'       => 'DATE',
                'null'          => false,
            ],
            'id_tipus_intervencio'          => [
                'type'           => 'INT',
                'constraint'     => 2,
                'unsigned'       => true,
                'null'          => false,
            ],
            'id_curs'          => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null'          => false,
            ],
            'correu_alumne'       => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
                'null'          => true,
            ],
            'id_xtec'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '16',
                'null'          => true,
            ],
            
        ]);
        //$this->forge->addForeignKey('id_tiquet', 'tiquet', 'id_tiquet');
        $this->forge->addForeignKey('id_tipus_intervencio', 'tipus_intervencio', 'id_tipus_intervencio');
        $this->forge->addForeignKey('id_curs', 'curs', 'id_curs');
        $this->forge->addForeignKey('correu_alumne', 'alumne', 'correu_alumne');
        $this->forge->addForeignKey('id_xtec', 'professor', 'id_xtec');
        $this->forge->addKey('id_intervencio', true);
        $this->forge->createTable('intervencio');
    }

    /**
     * Funció per revertir la migració.
     *
     * @author Blai Burgués Vicente
     */
    public function down()
    {
        $this->forge->dropTable('tipus_intervencio');
        $this->forge->dropTable('curs');
        $this->forge->dropTable('intervencio');
    }
}
