<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirTipusIntervencioSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_tipus_intervencio' => $i,    //$fake -> randomDigit()
                'nom_tipus_intervencio' => $fake -> word()
            ];
            $this->db->table('tipus_intervencio')->insert($data);
        }
    }
}
