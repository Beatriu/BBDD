<?php

namespace App\Models;

use CodeIgniter\Model;

class CursModel extends Model
{
    protected $table            = 'curs';
    protected $primaryKey       = 'id_curs';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['cicle','titol','curs'];

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

    public function addCurs($d1,$d2,$d3)
    {
        $this->insert([
            "cicle" => $d1,
            "titol" => $d2,
            "curs" => $d3
        ]);
    }

    public function obtenirCursos() {
        return $this->findAll();
    }
}
