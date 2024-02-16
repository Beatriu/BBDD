<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirIntervencioSeeder extends Seeder
{
    
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."intervencio.csv", "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\IntervencioModel;
                $model->addIntervencio($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]);
            }
            $firstline = false;
        }

        fclose($csvFile);
        /*$fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_intervencio' => $fake -> uuid(),    
                'descripcio_intervencio' => $fake -> realText(512),
                'id_tiquet' => $fake -> randomDigit(12),    
                'data_intervencio' => $fake->date('d-m-y h:i:s'),
                'id_tipus_intervencio' => $fake -> randomDigit(2),    
                'id_curs' => $fake -> $fake->randomDigit(),
                'correu_alumne' => $fake -> email(),    
                'id_xtec' => $fake -> $fake->uuid(),
            ];
            $this->db->table('intervencio')->insert($data);
        }*/
    }
}
