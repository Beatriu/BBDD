<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirTiquetSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {
            $data = [
                'id_tiquet' => $fake->uuid(),
                'codi_equip' =>$fake->word(),
                'descripcio_avaria' => $fake->realText(512),
                'nom_persona_contacte_centre' =>$fake->firstName(),
                'correu_persona_contacte_centre' =>$fake->email(),
                'data_alta' =>$fake->date('d-m-y h:i:s'),
                'data_ultima_modificacio' => $fake->date('d-m-y h:i:s'),
                'id_tipus_dispositiu' =>$fake->randomDigit(),
                'id_estat' => $fake->randomDigit(),
                'codi_centre_emissor' =>$fake->uuid(),
                'codi_centre_reparador' => $fake->uuid()
            ];

            $this->db->table('tiquet')->insert($data);
        }
    }
}
