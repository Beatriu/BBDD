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
                'id_tipus_inventari' => $i,    //$fake -> randomDigit()
                'nom_tipus_inventari' => $fake -> word()
            ];
            $this->db->table('tipus_inventari')->insert($data);
        }
    }
}
