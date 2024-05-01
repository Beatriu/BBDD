<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EstatTipusDispositiuTiquetMigration extends Migration
{
    /**
     * Funció per arrencar la migració.
     *
     * @author Beatriu Badia Sala
     */
    public function up()
    {
        // Taula estat
        $this->forge->addField([
            'id_estat' =>[
                'type' => 'INTEGER',
                'constraint' => 8,
                'null' => false,
                'auto_increment' => TRUE
            ],
            'nom_estat' =>[
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => false,
            ]
        ]);
        $this->forge->addKey('id_estat', true);
        $this->forge->createTable('estat');

        // Taula tipus dispositiu
        $this->forge->addField([
            'id_tipus_dispositiu' =>[
                'type' => 'INTEGER',
                'constraint' => 8,
                'null' => false,
                'auto_increment' => TRUE
            ],
            'nom_tipus_dispositiu' =>[
                'type' => 'VARCHAR',
                'constraint' => '32',
            ]
        ]);
        $this->forge->addKey('id_tipus_dispositiu', true);
        $this->forge->createTable('tipus_dispositiu');

        // Taula tiquet
        $this->forge->addField([
            'id_tiquet' =>[
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'codi_equip' =>[
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => false,
            ],
            'descripcio_avaria' =>[
                'type' => 'VARCHAR',
                'constraint' => '512',
                'null' => false,
            ],
            'nom_persona_contacte_centre' =>[
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => false,
            ],
            'correu_persona_contacte_centre' =>[
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => false,
            ],
            'data_alta' =>[
                'type' => 'DATETIME',
                'null' => false,
            ],
            'data_ultima_modificacio' =>[
                'type' => 'DATETIME',
                'null' => true,
            ],
            'id_tipus_dispositiu' =>[
                'type' => 'INTEGER',
                'constraint' => 8,
                'null' => false,
            ],
            'id_estat' =>[
                'type' => 'INTEGER',
                'constraint' => 8,
                'null' => false,
            ],
            'codi_centre_emissor' =>[
                'type' => 'VARCHAR',
                'constraint' => '8',
                'null' => true,
            ],
            'codi_centre_reparador' =>[
                'type' => 'VARCHAR',
                'constraint' => '8',
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id_tiquet', true);
        $this->forge->addForeignKey('id_tipus_dispositiu','tipus_dispositiu','id_tipus_dispositiu');
        $this->forge->addForeignKey('id_estat','estat','id_estat');
        $this->forge->addForeignKey('codi_centre_emissor','centre','codi_centre');
        $this->forge->addForeignKey('codi_centre_reparador','centre','codi_centre');
        $this->forge->createTable('tiquet');
    }

    /**
     * Funció per revertir la migració.
     *
     * @author Beatriu Badia Sala
     */
    public function down()
    {
        $this->forge->dropTable('estat');
        $this->forge->dropTable('tipus_dispositiu');
        $this->forge->dropTable('tiquet');
    }
}