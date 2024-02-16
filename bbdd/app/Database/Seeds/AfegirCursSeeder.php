<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirCursSeeder extends Seeder
{
    public function run()
    {
<<<<<<< Updated upstream
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."curs.csv", "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\CursModel;
                $model->addCurs($data[1], $data[2], $data[3]);
            }
            $firstline = false;
        }

        fclose($csvFile);

        /*$fake = Factory::create("es_ES");
=======
    
        $fake = Factory::create("es_ES");
>>>>>>> Stashed changes

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_curs' => $fake -> randomDigit(),    //$fake -> randomDigit()
                'cicle' => $fake -> word(),
                'titol' => $fake -> words(3, true),    //$fake -> randomDigit()
                'curs' => $fake -> $fake->randomElement([1, 2])
            ];
            $this->db->table('curs')->insert($data);
        }*/
    }
}
