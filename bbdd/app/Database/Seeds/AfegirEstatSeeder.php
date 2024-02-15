<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirEstatSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."estat.csv", "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\EstatModel;
                $model->addEstat($data[1]);
            }
            $firstline = false;
        }

        fclose($csvFile);

        /*$fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_estat' => $fake ->randomDigit(8),
                'nom_estat' => $fake->word(2),
            ];
            $this->db->table('estat')->insert($data);
        }*/
    }
}
