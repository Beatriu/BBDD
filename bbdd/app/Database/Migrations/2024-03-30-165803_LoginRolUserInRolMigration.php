<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LoginRolUserInRolMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_login' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'login' => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
                'unique' => true,
                'null' => false,
            ],
            'contrasenya' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ]
        ]);
        $this->forge->addKey('id_login', true);
        $this->forge->createTable('login'); 
        
        $this->forge->addField([
            'id_rol' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'nom_rol' => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id_rol', true);
        $this->forge->createTable('rol');  

        $this->forge->addField([
            'id_login_relacio' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false,
            ],
            'id_rol_relacio' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false,
            ],
        ]);
        $this->forge->addForeignKey('id_login_relacio', 'login', 'id_login');
        $this->forge->addForeignKey('id_rol_relacio', 'rol', 'id_rol');
        $this->forge->createTable('login_in_rol');  
    }

    public function down()
    {
        $this->forge->dropTable('login');
        $this->forge->dropTable('rol');
        $this->forge->dropTable('login_in_rol');
    }
}
