<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirLlistaAdmesosSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."llista_admesos.csv", "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\LlistaAdmesosModel;
                $model->addLlistaAdmesos($data[0], $data[1], $data[2]);
            }
            $firstline = false;
        }

        fclose($csvFile);
        /*$fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'correu_professor' => $fake->email(),
                'data_entrada' => $fake->date('d-m-y h:i:s'),
                'codi_centre' => $fake->uuid(),
            ];
            
            $this->db->table('llista_admesos')->insert($data);
        }*/
    }
}
