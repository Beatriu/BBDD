<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirInventariSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."inventari.csv", "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\InventariModel;
                $model->addInventari($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
            }
            $firstline = false;
        }

        fclose($csvFile);
        /*$fake = Factory::create("es_ES");

        for ($i = 0; $i < 10; $i++) {

            $data = [
                'id_inventari' => $fake->uuid(),
                'descripcio_inventari' => $fake->realText(512),
                'data_compra' => $fake->date('d-m-y h:i:s'),
                'preu' => $fake->randomFloat(),
                'codi_centre' => $fake->uuid(),
                'id_tipus_inventari' => $fake->randomDigit(2),
                'id_intervencio' => $fake->randomDigit(8)
            ];
            $this->db->table('inventari')->insert($data);
        }*/
    }
}
