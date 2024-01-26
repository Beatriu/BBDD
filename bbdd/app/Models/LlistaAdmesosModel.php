<?php

namespace App\Models;

use CodeIgniter\Model;

class LlistaAdmesosModel extends Model
{
    protected $table            = 'llistaadmesos';
    protected $primaryKey       = 'correu_professor';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['correu_professor','data_entrada','codi_centre'];

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

    public function addLlistaAdmesos($d1,$d2,$d3)
    {
        $this->insert([
            "correu_professor" => $d1,
            "data_entrada" => $d2,
            "codi_centre" => $d3
        ]);
    }
}
