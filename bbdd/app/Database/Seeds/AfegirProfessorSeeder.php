<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirProfessorSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_xtec' => $fake->uuid(),
                'nom_professor' => $fake->word(),
                'cognoms_professor' => $fake->lastName(),
                'correu_professor' => $fake->email(),
                'codi_centre' => $fake->uuid(),
            ];
            
            $this->db->table('professor')->insert($data);
        }
    }
}
