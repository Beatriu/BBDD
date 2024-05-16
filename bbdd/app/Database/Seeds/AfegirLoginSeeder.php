<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AfegirLoginSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."login.csv", "r"); // read file from /writable/uploads folder.

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\LoginModel();
                $model->addLoginCSV($data[0],$data[1],$data[2]);
                //echo $data[2];
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
