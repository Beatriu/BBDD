<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirComarcaSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_comarca' => $fake->randomDigit(2, false),
                'nom_comarca' => $fake->state(),
            ];

            $this->db->table('comarca')->insert($data);
        }
    }
}
