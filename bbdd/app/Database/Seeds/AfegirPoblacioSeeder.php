<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirPoblacioSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_poblacio' => $fake->randomDigit(5, true),
                'nom_poblacio' => $fake->city(),
                'id_comarca' => $fake->randomDigit(2, false),
            ];
            
            $this->db->table('poblacio')->insert($data);
        }
    }
}
