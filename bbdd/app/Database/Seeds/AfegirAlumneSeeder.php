<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirAlumneSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."alumnes.csv", "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\AlumneModel;
                $model->addAlumne($data[0], $data[1]);
            }
            $firstline = false;
        }

        fclose($csvFile);

        //SEEDER AMB FAKER
        /*$fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'correu_alumne' => $fake->email(),
                'codi_centre' => $fake->uuid(),
            ];
            
            $this->db->table('alumne')->insert($data);
        }*/
    }
}
