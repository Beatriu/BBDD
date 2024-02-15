<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirTipusInventariSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."tipus_inventari.csv", "r"); // read file from /writable/uploads folder.

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\TipusInventariModel;
                $model->addTipusInventari($data[1]);
            }
            $firstline = false;
        }

        fclose($csvFile);

        /*$fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_tipus_inventari' => $fake->randomDigit(8),
                'nom_tipus_inventari' => $fake -> word(2),
            ];
            $this->db->table('tipus_inventari')->insert($data);
        }*/
    }
}
