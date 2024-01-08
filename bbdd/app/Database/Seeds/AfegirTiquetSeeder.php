<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirTiquetSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 0; $i < 10; $i++) {

            $data = [
                'id_tiquet' => $fake->uuid(),
                'codi_equip' => $fake->uuid(),
                'descripcio_avaria' => $fake->realText(512),
                'nom_persona_contacte_centre' => $fake->name(),
                'correu_persona_contacte_centre' => $fake->email(),
                'data_alta' => $fake->date('d-m-y h:i:s'),
                'data_ultima_modificacio' => $fake->date('d-m-y h:i:s'),
                'id_tipus_dispositiu' => $fake->randomDigit(8),
                'id_estat' => $fake->randomDigit(8),
                'codi_centre_emissor' => $fake->uuid(),
                'codi_centre_reparador' => $fake->uuid()
            ];
            $this->db->table('tiquet')->insert($data);
        }
    }
}
