<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirCursSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_curs' => $fake -> randomDigit(),    //$fake -> randomDigit()
                'cicle' => $fake -> word(),
                'titol' => $fake -> words(3, true),    //$fake -> randomDigit()
                'curs' => $fake -> $fake->randomElement([1, 2])
            ];
            $this->db->table('curs')->insert($data);
        }
    }
}
