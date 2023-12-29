<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EstatTipusDispositiuTiquetMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_estat' =>[
                'type' => 'INTEGER',
                'constraint' => 8,
                'null' => false,
            ],
            'nom_estat' =>[
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => false,
            ]
        ]);
        $this->forge->addKey('id_estat', true);
        $this->forge->createTable('estat');

        $this->forge->addField([
            'id_tipus_dispositiu' =>[
                'type' => 'INTEGER',
                'constraint' => 8,
                'null' => false,
            ],
            'nom_tipus_dispositiu' =>[
                'type' => 'VARCHAR',
                'constraint' => '32',
            ]
        ]);
        $this->forge->addKey('id_tipus_dispositiu', true);
        $this->forge->createTable('tipus_dispositiu');

        $this->forge->addField([
            'id_tiquet' =>[
                'type' => 'INTEGER',
                'constraint' => 12,
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
                'type' => 'DATE',
                'null' => false,
            ],
            'data_ultima_modificacio' =>[
                'type' => 'DATE',
                'null' => false,
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
                'null' => false,
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
        $this->forge->addForeignKey('codi_centre_emisso','centre','codi_centre_emisso');
        $this->forge->addForeignKey('codi_centre_reparador','centre','codi_centre_reparador');
        $this->forge->createTable('tiquet');
    }

    public function down()
    {
        $this->forge->dropTable('estat');
        $this->forge->dropTable('tipus_dispositiu');
        $this->forge->dropTable('tiquet');
    }
}
