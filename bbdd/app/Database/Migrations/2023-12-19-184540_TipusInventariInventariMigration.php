<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TipusInventariInventariMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_tipus_inventari' =>[
                'type' => 'INTEGER',
                'constraint' => 2,
                'null' => false,
            ],
            'nom_tipus_inventari' =>[
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => false,
            ]
        ]);
        $this->forge->addKey('id_tipus_inventari', true);
        $this->forge->createTable('tipus_Inventari');

        $this->forge->addField([
            'id_inventari' =>[
                'type' => 'VARCAHR',
                'constraint' => '8',
                'null' => false,
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
                'constraint' => '8',
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id_inventari', true);
        $this->forge->addForeignKey('codi_centre','centre','codi_centre');
        $this->forge->addForeignKey('id_tipus_inventari','tipus_inventari','id_tipus_inventari');
        $this->forge->addForeignKey('id_intervencio','intervencio','id_intervencio');
        $this->forge->createTable('tiquet');
    }

    public function down()
    {
        $this->forge->dropTable('tipus_inventari');
        $this->forge->dropTable('inventari');
    }
}
