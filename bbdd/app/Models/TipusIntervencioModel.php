<?php

namespace App\Models;

use CodeIgniter\Model;

class TipusIntervencioModel extends Model
{
    protected $table            = 'tipus_intervencio';
    protected $primaryKey       = 'id_tipus_intervencio';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_tipus_intervencio','nom_tipus_intervencio'];

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

    public function addTipusIntervencio($d1)
    {
        $this->insert([
            "nom_tipus_intervencio" => $d1
        ]);
    }

    public function obtenirTipusIntervencio() {
        return $this->findAll();
    }
}
