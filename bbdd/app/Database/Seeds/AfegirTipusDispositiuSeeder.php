<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirTipusDispositiuSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_tipus_dispositiu' => $fake ->randomDigit(8),
                'nom_tipus_dispositiu' => $fake -> word(2)
            ];
            $this->db->table('tipus_dispositiu')->insert($data);
        }
    }
}
