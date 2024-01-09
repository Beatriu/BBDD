<?php

namespace App\Models;

use CodeIgniter\Model;

class TiquetModel extends Model
{
    protected $table            = 'tiquet';
    protected $primaryKey       = 'id_tiquet';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_tiquet','codi_equip','descripcio_avaria','nom_persona_contacte_centre','correu_persona_contacte_centre','data_alta','data_ultima_modificacio','id_tipus_dispositiu','id_estat','codi_centre_emissor','codi_centre_reparador'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function addTiquet($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$d11)
    {
        $this->insert([
            "id_tiquet" => $d1,
            "codi_equip" => $d2,
            "descripcio_avaria" => $d3,
            "nom_persona_contacte_centre" => $d4,
            "correu_persona_contacte_centre" => $d5,
            "data_alta" => $d6,
            "data_ultima_modificacio" => $d7,
            "id_tipus_dispositiu" => $d8,
            "id_estat" => $d9,
            "codi_centre_emissor" => $d10,
            "codi_centre_reparador" => $d11
        ]);
    }
}
