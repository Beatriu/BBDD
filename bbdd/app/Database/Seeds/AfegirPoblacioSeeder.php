<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AfegirPoblacioSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."centres_poblacions.csv", "r"); // read file from /writable/uploads folder.

        $firstline = true;

        while (($data = fgetcsv($csvFile, 6000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\PoblacioModel();
                if ($model->getPoblacio($data[9]) == null) {
                    $model->addPoblacio($data[9], $data[3], $data[10], $data[7]);
                }
                
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
