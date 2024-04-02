<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AfegirLoginInRolSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."login_in_rol.csv", "r"); // read file from /writable/uploads folder.

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\LoginInRolModel();
                $model->addLoginInRol($data[0],$data[1]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
