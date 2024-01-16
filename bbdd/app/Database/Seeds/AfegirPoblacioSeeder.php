<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AfegirPoblacioSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."poblacions.csv", "r"); // read file from /writable/uploads folder.

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\PoblacioModel();
                $model->addPoblacio($data[0], $data[1], $data[3], $data[4]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
