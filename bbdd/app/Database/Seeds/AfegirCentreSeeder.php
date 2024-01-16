<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AfegirCentreSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."centres_bona.csv", "r"); // read file from /writable/uploads folder.

        $firstline = true;

        while (($data = fgetcsv($csvFile, 6000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\CentreModel;
                $model->addCentre($data[0], $data[1], 1, 0, $data[4], $data[2], '', '', $data[5], $data[11]);
                echo $data[0].$data[5];
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
