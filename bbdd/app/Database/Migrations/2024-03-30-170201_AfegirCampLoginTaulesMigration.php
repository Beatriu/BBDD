<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AfegirCampLoginTaulesMigration extends Migration
{
    public function up()
    {
        $fields = [
            'login'          => [
                'type'           => 'VARCHAR',
                'constraint' => 32,
            ],
        ];
        $this->forge->addColumn('sstt', $fields);

        $fields = [
            'login'          => [
                'type'           => 'VARCHAR',
                'constraint' => 32,
            ],
        ];
        $this->forge->addColumn('centre', $fields);

        $fields = [
            'login'          => [
                'type'           => 'VARCHAR',
                'constraint' => 32,
            ],
        ];
        $this->forge->addColumn('professor', $fields);
    
        $fields = [
            'login'          => [
                'type'           => 'VARCHAR',
                'constraint' => 32,
            ],
        ];
        $this->forge->addColumn('alumne', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('sstt', ['login']);
        $this->forge->dropColumn('centre', ['login']);
        $this->forge->dropColumn('professor', ['login']);
        $this->forge->dropColumn('alumne', ['login']);
    }
}
