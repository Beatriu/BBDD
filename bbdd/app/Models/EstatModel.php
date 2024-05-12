<?php

namespace App\Models;

use CodeIgniter\Model;

class EstatModel extends Model
{
    protected $table            = 'estat';
    protected $primaryKey       = 'id_estat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom_estat'];

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

    public function addEstat($d1)
    {
        $this->insert([
            "nom_estat" => $d1
        ]);
    }

    public function getEstats() {
        return $this->findAll();
    }

    public function getProfessorEstats() {
        return $this->select(['id_estat', 'nom_estat'])
        ->where('nom_estat', 'Pendent de reparar')
        ->orWhere('nom_estat', 'Reparant')
        ->orWhere('nom_estat', 'Reparat i pendent de recollir')
        ->findAll();
    }

    public function obtenirEstatPerId($id_estat) {
        return $this->select('nom_estat')->where('id_estat', $id_estat)->first()['nom_estat'];
    }
}
