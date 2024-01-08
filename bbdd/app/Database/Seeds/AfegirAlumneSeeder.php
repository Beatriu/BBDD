<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirAlumneSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'correu_alumne' => $fake->email(),
                'codi_centre' => $fake->uuid(),
            ];
            
            $this->db->table('alumne')->insert($data);
        }
    }
}
