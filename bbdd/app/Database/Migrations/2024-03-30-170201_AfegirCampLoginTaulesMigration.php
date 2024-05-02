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
        $this->forge->addColumn('centre', $fields);

    }

    public function down()
    {
        $this->forge->dropColumn('centre', ['login']);
    }
}
