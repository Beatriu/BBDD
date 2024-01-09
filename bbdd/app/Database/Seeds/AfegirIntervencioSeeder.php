<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirIntervencioSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_intervencio' => $fake -> uuid(),    
                'descripcio_intervencio' => $fake -> realText(512),
                'id_tiquet' => $fake -> randomDigit(12),    
                'data_intervencio' => $fake->date('d-m-y h:i:s'),
                'id_tipus_intervencio' => $fake -> randomDigit(2),    
                'id_curs' => $fake -> $fake->randomDigit(),
                'correu_alumne' => $fake -> email(),    
                'id_xtec' => $fake -> $fake->uuid(),
            ];
            $this->db->table('intervencio')->insert($data);
        }
    }
}
