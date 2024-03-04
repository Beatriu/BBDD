<?php

namespace App\Models;

use CodeIgniter\Model;

class TipusDispositiuModel extends Model
{
    protected $table            = 'tipus_dispositiu';
    protected $primaryKey       = 'id_tipus_dispositiu';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom_tipus_dispositiu'];

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
    
    public function addTipusDispositiu($d1)
    {
        $this->insert([
            "nom_tipus_dispositiu" => $d1
        ]);
    }

    public function getTipusDispositius() {
        return $this->findAll();
    }
}
