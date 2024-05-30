<?php

namespace App\Models;

use CodeIgniter\Model;

class InventariModel extends Model
{
    protected $table            = 'inventari';
    protected $primaryKey       = 'id_inventari';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_inventari','descripcio_inventari','data_compra','preu','codi_centre','id_tipus_inventari','id_intervencio'];

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

    public function addInventari($d1,$d2,$d3,$d4,$d5,$d6,$d7)
    {
        $this->insert([
            "id_inventari" => $d1,
            "descripcio_inventari" => $d2,
            "data_compra" => $d3,
            "preu" => $d4,
            "codi_centre" => $d5,
            "id_tipus_inventari" => $d6,
            "id_intervencio" => $d7
        ]);
    }

    public function obtenirInventari()
    {
        return $this->findAll();
    }

    public function obtenirInventariPerId($id_inventari)
    {
        return $this->where('id_inventari', $id_inventari)->first();
    }

    public function obtenirInventariCentre($codi_centre)
    {
        return $this->where('codi_centre', $codi_centre)->findAll();
    }

    public function obtenirInventariIntervencio($id_intervencio)
    {
        return $this->where('id_intervencio', $id_intervencio)->findAll();
    }

    public function editarInventariDesassignar($id_inventari) 
    {
        return $this->update($id_inventari, ["id_intervencio" => null]);
    }

    public function editarInventariAssignar($id_inventari, $id_intervencio) 
    {
        return $this->update($id_inventari, ["id_intervencio" => $id_intervencio]);
    }

    public function deleteInventari($id_inventari) 
    {
        return $this->delete(['id_inventari' => $id_inventari]);
    }
}
