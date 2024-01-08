<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirEstatSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_estat' => $fake ->randomDigit(8),
                'nom_estat' => $fake->word(2),
            ];
            $this->db->table('estat')->insert($data);
        }
    }
}
