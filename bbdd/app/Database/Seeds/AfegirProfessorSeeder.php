<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirProfessorSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."professors.csv", "r"); // read file from /writable/uploads folder.

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\ProfessorModel;
                $model->addProfessor($data[0], $data[1], $data[2], $data[3], $data[4]);
            }
            $firstline = false;
        }

        fclose($csvFile);

        /*$fake = Factory::create("es_ES");

        for ($i = 1; $i < 11; $i++) {

            $data = [
                'id_xtec' => $fake->uuid(),
                'nom_professor' => $fake->word(),
                'cognoms_professor' => $fake->lastName(),
                'correu_professor' => $fake->email(),
                'codi_centre' => $fake->uuid(),
            ];
            
            $this->db->table('professor')->insert($data);
        }
        */
    }
}
