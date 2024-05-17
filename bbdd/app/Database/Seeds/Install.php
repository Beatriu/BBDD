<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Install extends Seeder
{
    public function run()
    {
        $this->call("AfegirComarcaSeeder");
        $this->call("AfegirPoblacioSeeder");
        $this->call("AfegirSSTTSeeder");
        $this->call("AfegirCentreSeeder");

        $this->call("AfegirTipusDispositiuSeeder");
        $this->call("AfegirEstatSeeder");
        $this->call("AfegirTipusIntervencioSeeder");
        $this->call("AfegirCursSeeder");
        //$this->call("AfegirLlistaAdmesosSeeder");
        $this->call("AfegirTipusInventariSeeder");

        //$this->call("AfegirTiquetSeeder");
        //$this->call("AfegirProfessorSeeder");
        //$this->call("AfegirAlumneSeeder");

        //$this->call("AfegirIntervencioSeeder");
        //$this->call("AfegirInventariSeeder");

        //$this->call("AfegirBackTicketSeeder");

        $this->call("AfegirRolSeeder");
        $this->call("AfegirLoginSeeder");
        $this->call("AfegirLoginInRolSeeder");
    }
}
