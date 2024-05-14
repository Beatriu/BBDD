<?php

namespace App\Models;

use CodeIgniter\Model;

class TipusInventariModel extends Model
{
    protected $table            = 'tipus_inventari';
    protected $primaryKey       = 'id_tipus_inventari';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_tipus_inventari','nom_tipus_inventari'];

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

    public function addTipusInventari($d1)
    {
        $this->insert([
            "nom_tipus_inventari" => $d1
        ]);
    }

    public function obtenirTipusInventari() {
        return $this->findAll();
    }
}
