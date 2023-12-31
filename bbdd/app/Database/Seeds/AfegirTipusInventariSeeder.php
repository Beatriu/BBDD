<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirTipusInventariSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_tipus_inventari' => $fake->randomDigit(8),
                'nom_tipus_inventari' => $fake -> word(2),
            ];
            $this->db->table('tipus_inventari')->insert($data);
        }
    }
}
