<?php

namespace App\Models;

use CodeIgniter\Model;

class SSTTModel extends Model
{
    protected $table            = 'sstt';
    protected $primaryKey       = 'id_sstt';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_sstt','nom_sstt','adreca_fisica_sstt','telefon_sstt','correu_sstt'];

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

    public function addSSTT($d1,$d2,$d3,$d4,$d5)
    {
        $this->insert([
            "id_sstt" => $d1,
            "nom_sstt" => $d2,
            "adreca_fisica_sstt" => $d3,
            "telefon_sstt" => $d4,
            "correu_sstt" => $d5
        ]);
    }

    public function obtenirSSTTPerCorreu($correu) {
        return $this->where('correu_sstt', $correu)->first();
    }
}
