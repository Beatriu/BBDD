<?php

namespace App\Models;

use CodeIgniter\Model;

class PoblacioModel extends Model
{
    protected $table            = 'poblacio';
    protected $primaryKey       = 'id_poblacio';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_poblacio', 'codi_postal', 'nom_poblacio','id_comarca','id_sstt'];

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

    public function addPoblacio($d1,$d2,$d3,$d4,$d5)
    {
        $this->insert([
            "id_poblacio" => $d1,
            "codi_postal" => $d2,
            "nom_poblacio" => $d3,
            "id_comarca" => $d4,
            "id_sstt" => $d5,
        ]);
    }

    public function getPoblacio($d1) {
        return $this->where('id_poblacio', $d1)->first();
    }

    public function obtenirPoblacions()
    {
        return $this->findAll();
    }

}
