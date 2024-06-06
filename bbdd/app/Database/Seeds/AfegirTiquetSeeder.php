<?php

namespace App\Database\Seeds;

use App\Models\CentreModel;
use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirTiquetSeeder extends Seeder
{
    public function run()
    {
        $centre_model = new CentreModel();
        $uuid_library = new \App\Libraries\UUID;
        $csvFile = fopen(WRITEPATH."uploads". DIRECTORY_SEPARATOR ."tiquet.csv", "r"); // read file from /writable/uploads folder.

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $model = new \App\Models\TiquetModel;
                $uuid = $uuid_library->v4();
                if ($data[9] != null) {
                    $id_sstt = $centre_model->obtenirCentre($data[9])['id_sstt'];
                } else if ($data[10] != null) {
                    $id_sstt = $centre_model->obtenirCentre($data[10])['id_sstt'];
                }
                $model->addTiquet($uuid, $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $id_sstt);
            }
            $firstline = false;
        }

        fclose($csvFile);

        /*$fake = Factory::create("es_ES");

        for ($i = 0; $i < 10; $i++) {

            $data = [
                'id_tiquet' => $fake->randomDigit(12),
                'codi_equip' => $fake->uuid(),
                'descripcio_avaria' => $fake->realText(512),
                'nom_persona_contacte_centre' => $fake->name(),
                'correu_persona_contacte_centre' => $fake->email(),
                'data_alta' => $fake->date('d-m-y h:i:s'),
                'data_ultima_modificacio' => $fake->date('d-m-y h:i:s'),
                'id_tipus_dispositiu' => $fake->randomDigit(8),
                'id_estat' => $fake->randomDigit(8),
                'codi_centre_emissor' => $fake->uuid(),
                'codi_centre_reparador' => $fake->uuid()
            ];
            $this->db->table('tiquet')->insert($data);
        }*/
    }
}
