<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirLlistaAdmesosSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'correu_professor' => $fake->email(),
                'data_entrega' => $fake->date('d-m-y h:i:s'),
                'codi_centre' => $fake->uuid(),
            ];
            
            $this->db->table('llista_admesos')->insert($data);
        }
    }
}
