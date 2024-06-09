<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AfegirCentreSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."centres_poblacions.csv", "r"); // read file from /writable/uploads folder.

        $firstline = true;

        while (($data = fgetcsv($csvFile, 6000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\CentreModel;
                /*$taller = 0;
                if ($data[0] == 25002799 || $data[0] == 25006288) {
                    $taller = 1;
                }
                $model->addCentre($data[0], $data[1], 1, $taller, $data[4], $data[2], '', '', $data[5], $data[9], $data[7], $data[11]);*/
                $model->editarCentreComarca($data[0], $data[7]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
