<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AfegirBackTicketSeeder extends Seeder
{
    public function run()
    {
        $fake = Factory::create("es_ES");

        for ($i = 0; $i < 10; $i++) {
            $data = [ 
                'tipus_alerta'  => $fake->name(),
                'data_backticket' => $fake->date(),
                'informacio' => $fake->realtext(100)
            ];
            $this->db->table('backticket')->insert($data);
        }
    }
}
