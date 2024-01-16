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
    }
}
