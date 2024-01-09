<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirCentreSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'codi_centre' => $fake->uuid(),
                'nom_centre' => $fake->word(),
                'actiu' => $fake->randomElement([0, 1]),
                'taller' => $fake->randomElement([0, 1]),
                'telefon_centre' => $fake->randomNumber(12, true),
                'adreca_fisica_centre' => $fake->streetAddress(),
                'nom_persona_contacte_centre' => $fake->name(),
                'correu_persona_contacte_centre' => $fake->email(),
                'id_sstt' => $fake->uuid(),
                'id_poblacio' => $fake->randomDigit(5, true),
            ];
            
            $this->db->table('centre')->insert($data);
        }
    }
}
