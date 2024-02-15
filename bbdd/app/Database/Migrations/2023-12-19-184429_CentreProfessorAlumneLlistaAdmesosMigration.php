<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CentreProfessorAlumneLlistaAdmesosMigration extends Migration
{
    /**
     * Funció per arrencar la migració.
     *
     * @author Blai Burgués Vicente
     */
    public function up()
    {
        // Taula centre
        $this->forge->addField([
            'codi_centre'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '8',
                'null'          => false,
            ],
            'nom_centre'       => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'          => false,
            ],
            'actiu'       => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'          => false,
            ],
            'taller'       => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'          => false,
            ],
            'telefon_centre'       => [
                'type'       => 'VARCHAR',
                'constraint' => '12',
                'null'          => false,
            ],
            'adreca_fisica_centre'       => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'          => false,
            ],
            'nom_persona_contacte_centre'       => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'          => false,
            ],
            'correu_persona_contacte_centre'       => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
                'null'          => false,
            ],
            'id_sstt'       => [
                'type'       => 'VARCHAR',
                'constraint' => '4',
                'null'          => false,
            ],
            'id_poblacio'          => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null'          => false,
            ],
        ]);
        $this->forge->addForeignKey('id_sstt', 'sstt', 'id_sstt');
        $this->forge->addForeignKey('id_poblacio', 'poblacio', 'id_poblacio');
        $this->forge->addKey('codi_centre', true);
        $this->forge->createTable('centre');

        // Taula professor
        $this->forge->addField([
            'id_xtec'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '16',
                'null'          => false,
            ],
            'nom_professor'       => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'          => false,
            ],
            'cognoms_professor'       => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'          => false,
            ],
            'correu_professor'       => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
                'null'          => false,
            ],
            'codi_centre'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '8',
                'null'          => false,
            ],
            
        ]);
        $this->forge->addForeignKey('codi_centre', 'centre', 'codi_centre');
        $this->forge->addKey('id_xtec', true);
        $this->forge->createTable('professor');

        // Taula alumne
        $this->forge->addField([
            'correu_alumne'       => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
                'null'          => false,
            ],
            'codi_centre'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '8',
                'null'          => false,
            ],
            
        ]);
        $this->forge->addForeignKey('codi_centre', 'centre', 'codi_centre');
        $this->forge->addKey('correu_alumne', true);
        $this->forge->createTable('alumne');

        // Taula llista admesos
        $this->forge->addField([
            'correu_professor'       => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
                'null'          => false,
            ],
            'data_entrada'       => [
                'type'       => 'DATE',
                'null'          => false,
            ],
            'codi_centre'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '8',
                'null'          => false,
            ],
            
        ]);
        $this->forge->addForeignKey('codi_centre', 'centre', 'codi_centre');
        $this->forge->addKey('correu_professor', true);
        $this->forge->createTable('llista_admesos');
    }

    /**
     * Funció per revertir la migració.
     *
     * @author Blai Burgués Vicente
     */
    public function down()
    {
        $this->forge->dropTable('centre');
        $this->forge->dropTable('professor');
        $this->forge->dropTable('alumne');
        $this->forge->dropTable('llista_admesos');
    }
}
