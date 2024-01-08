<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirInventariSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 0; $i < 10; $i++) {

            $data = [
                'id_inventari' => $fake->uuid(),
                'descripcio_inventari' => $fake->realText(512),
                'data_compra' => $fake->date('d-m-y h:i:s'),
                'preu' => $fake->randomFloat(),
                'codi_centre' => $fake->uuid(),
                'id_tipus_inventari' => $fake->randomDigit(),
                'id_intervencio' => $fake->randomDigit()
            ];
            $this->db->table('inventari')->insert($data);
        }
    }
}
