<?php

namespace App\Models;

use CodeIgniter\Model;

class IntervencioModel extends Model
{
    protected $table            = 'intervencio';
    protected $primaryKey       = 'id_intervencio';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_intervencio','descripcio_intervencio','id_tiquet','data_intervencio','id_tipus_intervencio','id_curs','correu_alumne','id_xtec'];

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


    public function addIntervencio($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8)
    {
        $this->insert([
            "id_intervencio" => $d1,
            "descripcio_intervencio" => $d2,
            "id_tiquet" => $d3,
            "data_intervencio" => $d4,
            "id_tipus_intervencio" => $d5,
            "id_curs" => $d6,
            "correu_alumne" => $d7,
            "id_xtec" => $d8
        ]);
    }

    public function obtenirIdIntervencioAlumne($correu_alumne)
    {
        return $this->where("correu_alumne", $correu_alumne)->findAll();
    }

    public function editarIntervencioCorreuNou($id_intervencio, $correu_nou) 
    {
        return $this->update($id_intervencio, ["correu_alumne" => $correu_nou]);
    }

}
