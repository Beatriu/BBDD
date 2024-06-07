<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TipusInventariInventariMigration extends Migration
{
    /**
     * Funció per arrencar la migració.
     *
     * @author Beatriu Badia Sala
     */
    public function up()
    {
        // Taula tipus inventari
        $this->forge->addField([
            'id_tipus_inventari' =>[
                'type' => 'INTEGER',
                'constraint' => 2,
                'null' => false,
                'auto_increment' => TRUE
            ],
            'nom_tipus_inventari' =>[
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => false,
            ],
            'actiu'       => [
                'type'       => 'TINYINT',
                'constraint' => 2,
                'null'          => false,
                'default' => '1',
            ],
        ]);
        $this->forge->addKey('id_tipus_inventari', true);
        $this->forge->createTable('tipus_inventari');

        // Taula inventari
        $this->forge->addField([
            'id_inventari' =>[
                'type' => 'VARCHAR',
                'constraint' => '36',
                'null' => false
            ],
            'descripcio_inventari' =>[
                'type' => 'VARCHAR',
                'constraint' => '512',
                'null' => false,
            ],
            'data_compra' =>[
                'type' => 'DATE',
                'null' => false,
            ],
            'preu' =>[
                'type' => 'FLOAT',
                'constraint' => 32,
                'null' => false,
            ],
            'codi_centre' =>[
                'type' => 'VARCHAR',
                'constraint' => '8',
                'null' => false,
            ],
            'id_tipus_inventari' =>[
                'type' => 'INTEGER',
                'constraint' => 2,
                'null' => false,
            ],
            'id_intervencio' =>[
                'type' => 'VARCHAR',
                'constraint' => '36',
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id_inventari', true);
        $this->forge->addForeignKey('codi_centre','centre','codi_centre');
        $this->forge->addForeignKey('id_tipus_inventari','tipus_inventari','id_tipus_inventari');
        $this->forge->addForeignKey('id_intervencio','intervencio','id_intervencio');
        $this->forge->createTable('inventari');
    }

    /**
     * Funció per revertir la migració.
     *
     * @author Beatriu Badia Sala
     */
    public function down()
    {
        $this->forge->dropTable('tipus_inventari');
        $this->forge->dropTable('inventari');
    }
}
