<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AfegirRolSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."rol.csv", "r"); // read file from /writable/uploads folder.

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\RolModel();
                $model->addRol($data[0]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
