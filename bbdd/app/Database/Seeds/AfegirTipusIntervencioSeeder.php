<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirTipusIntervencioSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."tipus_intervencio.csv", "r"); // read file from /writable/uploads folder.

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\TipusIntervencioModel;
                $model->addTipusIntervencio($data[1]);
            }
            $firstline = false;
        }

        fclose($csvFile);

        /*$fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_tipus_intervencio' => $i,    //$fake -> randomDigit()
                'nom_tipus_intervencio' => $fake -> word()
            ];
            $this->db->table('tipus_intervencio')->insert($data);
        }*/
    }
}
