<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirSSTTSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_sstt' => $fake -> uuid(),    //$fake -> randomDigit()
                'nom_sstt' => $fake -> word(),
                'adreca_fisica_sstt' => $fake ->streetAddress(),   
                'telefon_sstt' => $fake -> phoneNumber(),
                'correu_sstt' => $fake -> email(),
            ];
            $this->db->table('sstt')->insert($data);
        }
    }
}
